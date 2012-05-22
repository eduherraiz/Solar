<?php

/**
 * people actions.
 *
 * @package    solar
 * @subpackage people
 * @author     Edu Herraiz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class peopleActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->solar_peoples = Doctrine_Core::getTable('SolarPeople')
      ->createQuery('a')
      ->where('visible=1')
      ->orderBy('a.category_id')
      ->execute();
  
  
  $this->solar_categories = Doctrine_Core::getTable('SolarCategory')
      ->createQuery('a')
      ->orderBy('a.id')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->solar_people = Doctrine_Core::getTable('SolarPeople')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->solar_people);
  }

//  public function executeNew(sfWebRequest $request)
//  {
//    $this->form = new SolarPeopleForm();
//  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SolarPeopleForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($solar_people = Doctrine_Core::getTable('SolarPeople')->find(array($request->getParameter('id'))), sprintf('Object solar_people does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarPeopleForm($solar_people);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($solar_people = Doctrine_Core::getTable('SolarPeople')->find(array($request->getParameter('id'))), sprintf('Object solar_people does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarPeopleForm($solar_people);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($solar_people = Doctrine_Core::getTable('SolarPeople')->find(array($request->getParameter('id'))), sprintf('Object solar_people does not exist (%s).', $request->getParameter('id')));
    $solar_people->delete();

    $this->redirect('people/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $solar_people = $form->save();

      $this->redirect('people/edit?id='.$solar_people->getId());
    }
  }
}
