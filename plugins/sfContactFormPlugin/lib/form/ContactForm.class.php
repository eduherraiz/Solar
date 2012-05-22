<?php

class ContactForm extends sfForm
{
  
  public function configure()
  {
     $this->setWidgets(array(
        'name'    => new sfWidgetFormInput(),
        'email'   => new sfWidgetFormInput(),
        'subject' => new sfWidgetFormInput(),
        'message' => new sfWidgetFormTextarea(),
        'captcha' => new sfWidgetFormInput(),
     ));

     $this->widgetSchema->setNameFormat('contact[%s]');
     
     $this->setValidators(array(
        'name'   => new sfValidatorString(array('required' => true)),
        'email'  => new sfValidatorEmail(),
        'subject' => new sfValidatorString(array('required' => false)),
        'message' => new sfValidatorString(array('min_length' => 4)),
        'captcha' => new sfValidatorString(array('required' => false)),
    ));
  }
}
