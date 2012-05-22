<?php

/**
 * page actions.
 *
 * @package    solar
 * @subpackage page
 * @author     Edu Herraiz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pageActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->solar_pages = Doctrine_Core::getTable('SolarPage')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->pass = $request->getPostParameter('password');
    $this->solar_page = Doctrine_Core::getTable('SolarPage')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->solar_page);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SolarPageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SolarPageForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($solar_page = Doctrine_Core::getTable('SolarPage')->find(array($request->getParameter('id'))), sprintf('Object solar_page does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarPageForm($solar_page);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($solar_page = Doctrine_Core::getTable('SolarPage')->find(array($request->getParameter('id'))), sprintf('Object solar_page does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarPageForm($solar_page);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($solar_page = Doctrine_Core::getTable('SolarPage')->find(array($request->getParameter('id'))), sprintf('Object solar_page does not exist (%s).', $request->getParameter('id')));
    $solar_page->delete();

    $this->redirect('page/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $solar_page = $form->save();

      $this->redirect('page/edit?id='.$solar_page->getId());
    }
  }
}
