<?php

require_once dirname(__FILE__).'/../lib/peopleGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/peopleGeneratorHelper.class.php';

/**
 * people actions.
 *
 * @package    solar
 * @subpackage people
 * @author     Edu Herraiz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class peopleActions extends autoPeopleActions
{
  
  public function executeBatchDelete_publications(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');
 
    $q = Doctrine_Query::create()
      ->delete('SolarPublication p')
      ->whereIn('p.people_id', $ids);
 
    $q->execute();
    
//    foreach ($q->execute() as $job)
//    {
//      $job->extend(true);
//    }
 
    $this->getUser()->setFlash('notice', 'The selected people have been deleted her publications.');
 
    $this->redirect('solar_people');
  }
  
  public function executeListDeletePublications(sfWebRequest $request)
  {
    $people = $this->getRoute()->getObject();
    $q = Doctrine_Query::create()
      ->delete('SolarPublication p')
      ->whereIn('p.people_id', $people->id);
 
    $q->execute();
 
    $this->getUser()->setFlash('notice', 'The selected people have been deleted her publications.');
 
    $this->redirect('solar_people');
  }
    
}
