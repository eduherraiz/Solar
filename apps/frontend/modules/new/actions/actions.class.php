<?php

/**
 * new actions.
 *
 * @package    solar
 * @subpackage new
 * @author     Edu Herraiz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class newActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->solar_news = Doctrine_Core::getTable('SolarNew')
      ->createQuery('a')
      ->orderBy('a.id DESC')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->solar_new = Doctrine_Core::getTable('SolarNew')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->solar_new);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SolarNewForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SolarNewForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($solar_new = Doctrine_Core::getTable('SolarNew')->find(array($request->getParameter('id'))), sprintf('Object solar_new does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarNewForm($solar_new);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($solar_new = Doctrine_Core::getTable('SolarNew')->find(array($request->getParameter('id'))), sprintf('Object solar_new does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarNewForm($solar_new);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($solar_new = Doctrine_Core::getTable('SolarNew')->find(array($request->getParameter('id'))), sprintf('Object solar_new does not exist (%s).', $request->getParameter('id')));
    $solar_new->delete();

    $this->redirect('new/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $solar_new = $form->save();

      $this->redirect('new/edit?id='.$solar_new->getId());
    }
  }
}
