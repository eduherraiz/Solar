<?php

/**
 * researchline actions.
 *
 * @package    solar
 * @subpackage researchline
 * @author     Edu Herraiz
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class researchlineActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->solar_researchlines = Doctrine_Core::getTable('SolarResearchline')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SolarResearchlineForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SolarResearchlineForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->solar_researchline = Doctrine_Core::getTable('SolarResearchline')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->solar_researchline);
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($solar_researchline = Doctrine_Core::getTable('SolarResearchline')->find(array($request->getParameter('id'))), sprintf('Object solar_researchline does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarResearchlineForm($solar_researchline);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($solar_researchline = Doctrine_Core::getTable('SolarResearchline')->find(array($request->getParameter('id'))), sprintf('Object solar_researchline does not exist (%s).', $request->getParameter('id')));
    $this->form = new SolarResearchlineForm($solar_researchline);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($solar_researchline = Doctrine_Core::getTable('SolarResearchline')->find(array($request->getParameter('id'))), sprintf('Object solar_researchline does not exist (%s).', $request->getParameter('id')));
    $solar_researchline->delete();

    $this->redirect('researchline/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $solar_researchline = $form->save();

      $this->redirect('researchline/edit?id='.$solar_researchline->getId());
    }
  }
}
