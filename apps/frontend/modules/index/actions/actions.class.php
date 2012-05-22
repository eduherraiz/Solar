<?php

/**
 * index actions.
 *
 * @package    solar
 * @subpackage index
 * @author     Edu Herraiz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class indexActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->solar_index = Doctrine_Core::getTable('SolarIndex')->find(array(1));
    $this->solar_peoples = Doctrine_Core::getTable('SolarPeople')
      ->createQuery('a')
      ->where('visible=1')
      ->orderBy('a.category_id')
      ->execute();
    $this->solar_researchlines = Doctrine_Core::getTable('SolarResearchline')
      ->createQuery('a')
      ->execute();
    $this->solar_categories = Doctrine_Core::getTable('SolarCategory')
      ->createQuery('a')
      ->orderBy('a.id')
      ->execute();
    $this->solar_news = Doctrine_Core::getTable('SolarNew')
      ->createQuery('a')
      ->orderBy('a.id DESC')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->solar_index = Doctrine_Core::getTable('SolarIndex')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->solar_index);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SolarIndexForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SolarIndexForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($solar_index = Doctrine_Core::getTable('SolarIndex')->find(array($request->getParameter('id'))), sprintf('Object solar_index does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarIndexForm($solar_index);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($solar_index = Doctrine_Core::getTable('SolarIndex')->find(array($request->getParameter('id'))), sprintf('Object solar_index does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarIndexForm($solar_index);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($solar_index = Doctrine_Core::getTable('SolarIndex')->find(array($request->getParameter('id'))), sprintf('Object solar_index does not exist (%s).', $request->getParameter('id')));
    $solar_index->delete();

    $this->redirect('index/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $solar_index = $form->save();

      $this->redirect('index/edit?id='.$solar_index->getId());
    }
  }
}
