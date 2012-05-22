<?php
/**
 * sfDoctrineTreeActions 
 * 
 * @uses sfActions
 * @package 
 * @version $id$
 * @copyright 2008 Jacques B. Philip
 * @author Jacques B. Philip <jphilip@noatak.com> 
 * @license LGPL See LICENSE that came packaged with this software
 */
class sfDoctrineTreeActions extends sfActions
{
  public function executeAdd_child()
  {
    $this->forward404IfWrongRequest();
    
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $root = $this->getRequestParameter('root');
    $value = $this->getRequestParameter('value');
    $linkPartial = $this->getRequestParameter('linkPartial');
    
    $table = Doctrine::getTable($model);
    $record = $table->find($id);
    $child = new $model;
    $child->$field = $value;
    if (method_exists($model, 'customizeChildToAdd'))
      $child = $child->customizeChildToAdd($child); 
    $record->getNode()->addChild($child);
    $identifier = array_values($child->identifier());
    
    if ($child->state() == Doctrine_Record::STATE_TCLEAN)
      $response = "ERROR";
    else {
      if (!function_exists('javascript_tag'))
        sfLoader::loadHelpers('Javascript');
      sfLoader::loadHelpers('Partial');
      $partial = !empty($linkPartial) ? $linkPartial : 'sfDoctrineTree/link';
      $response = json_encode(array('id' => $identifier[0], 'partial' => get_partial($partial, array('record' => $child, 'model' => $model, 'field' => $field, 'root' => $root, 'identifier' => $identifier[0]))));  
    }
    $this->renderText($response); 
    return sfView::NONE;
  }

  public function executeRename()
  {
    $this->forward404IfWrongRequest();
    
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $value = $this->getRequestParameter('value');
    
    $identifier = Doctrine::getTable($model)->getIdentifier();
    $modelInstance = new $model();
    
    $q = Doctrine_Query::create();
    $q->update("$model")
      ->from("$model m")
      ->set("m.$field", "?", $value)
      ->where("m.$identifier = ?", $id);
   if (method_exists($model, 'customizeTreeUpdateQuery'))
     $q = $modelInstance->customizeTreeUpdateQuery($q);  
      
   $rows = $q->execute();
   $rows < 1 ? $this->renderText("Could not rename the record in database.") : $this->renderText("OK");
    return sfView::NONE;
  }
  
public function executeDelete()
  {
    $this->forward404IfWrongRequest();
    
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    
    $identifier = Doctrine::getTable($model)->getIdentifier();
    $modelInstance = new $model();
    
    $q = Doctrine_Query::create();
    $q->select("m.$identifier")
      ->from("$model m")
      ->where("m.$identifier = ?", $id);
   if (method_exists($model, 'customizeTreeSelectForDeleteQuery'))
     $q = $modelInstance->customizeTreeSelectForDeleteQuery($q);
      
   $row = $q->execute()->getFirst();
   if (!$row)
     $text = 'Record not found';
   else {
     try {
       if ($row->getNode()->delete())
         $text = 'OK';
       else
         $text = $row->$field;
     } catch (Exception $e) {
       $text = $row->$field;
     }   
   }
   $this->renderText($text); 
   return sfView::NONE;
  }
  
  public function executeSave_tree()
  {
    $this->forward404IfWrongRequest();
    
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $dragHistory = $this->getRequestParameter('dragHistory');
    $rootId = $this->getRequestParameter('rootId');
    $treeObj = Doctrine::getTable($model)->getTree();
    
    $items = json_decode($dragHistory);
    
    $conn = Doctrine_Manager::connection();
            
        try {
          $conn->beginTransaction();
          foreach ($items as $item) {
            $source = Doctrine::getTable($model)->find($item[0]);
            $operation = $item[1];
            $target = Doctrine::getTable($model)->find($item[2]);
            $source->getNode()->$operation($target);           
          }          
          $conn->commit();
          $this->renderText('OK');
          return sfView::NONE;
          
        } catch (Exception $e) {
          $conn->rollback();
          throw $e;
        }
  }
  
  private function forward404IfWrongRequest() {
  	if (! $this->getRequest ()->isXmlHttpRequest () || sfConfig::get ( 'sf_environment' ) == 'test' || $this->getRequest ()->getMethodName () != 'POST')
  	{
  	  $this->forward404 ();
    }
  }
}
