<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfJqueryTreeDoctrineManager actions.
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Gregory Schurgast <michkinn@gmail.com>
 * @author     Gordon Franke <info@nevalon.de>
 * @version    SVN: $Id: BasesfGuardForgotPasswordActions.class.php 18401 2009-05-18 14:12:09Z gimler $
 */
class BasesfJqueryTreeDoctrineManagerActions extends sfActions
{
  public function getTree($model, $rootId = null)
  {
    $tree = Doctrine_Core::getTable($model)->getTree();

    $options = array();
    if($rootId !== null)
    {
      $options['root_id'] = $rootId;
    }

    return $tree->fetchTree($options);
  }

  public function executeAdd_child()
  {
    $parent_id = $this->getRequestParameter('parent_id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $value = $this->getRequestParameter('value');
    $record = Doctrine_Core::getTable($model)->find($parent_id);

    $child = new $model;
    $child->set($field, $value);
    $record->getNode()->addChild($child);
    
    $this->json = json_encode($child->toArray());
    
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
  }
  
  public function executeAdd_root()
  {
    $model = $this->getRequestParameter('model');
    $data = $this->getRequestParameter( strtolower($model) );
    $tree = $this->getTree($model);
    
    $root = new $model;
    $root->synchronizeWithArray( $data );
		$root->save();
    
    Doctrine_Core::getTable($model)->getTree()->createRoot($root);
    $this->records = $this->getTree($model);

    $this->json = json_encode($record->toArray());
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
  }

  public function executeEdit_field()
  {
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    $field = $this->getRequestParameter('field');
    $value = $this->getRequestParameter('value');

    $record = Doctrine_Core::getTable($model)->find($id);
    $record->set($field, $value);
    $record->save();

    $this->json = json_encode($record->toArray());
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
  }

  public function executeDelete()
  {
    $id = $this->getRequestParameter('id');
    $model = $this->getRequestParameter('model');
    
    $record = Doctrine_Core::getTable($model)->find($id);
    $record->getNode()->delete();
    $this->json = json_encode(array());
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
  }

  public function executeMove()
  {
    $id = $this->getRequestParameter('id');
    $to_id = $this->getRequestParameter('to_id');
    $model = $this->getRequestParameter('model');
    $movetype = $this->getRequestParameter('movetype');
    
    $record = Doctrine_Core::getTable($model)->find($id);
    $dest = Doctrine_Core::getTable($model)->find($to_id);
    
    if( $movetype == 'inside' )
    {
      //$prev = $record->getNode()->getPrevSibling();
      $record->getNode()->moveAsLastChildOf($dest);
    }
    else if( $movetype == 'after' )
    {
      $record->getNode()->moveAsNextSiblingOf($dest);
    }
    
    else if( $movetype == 'before' )
    {
      //$next = $record->getNode()->getNextSibling();
      $record->getNode()->moveAsPrevSiblingOf($dest);
    }
    $this->json = json_encode($record->toArray());
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $this->setTemplate('json');
  }
}
