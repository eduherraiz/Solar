<?php 

class BaseContactActions extends sfActions
{


   public function executeIndex($request)
   {
      $this->form = new ContactForm();


      if($request->isMethod('post')):

          $this->form->bind($request->getParameter('contact'));
        
          if($this->form->isValid()):
   
             if($this->form->getValue('captcha') == $this->getUser()->getAttribute('security_code')):
             $mensagem = Swift_Message:: newInstance()
		  ->setFrom($this->form->getValue('email'))
		  ->setTo(sfConfig::get('app_contact_form_email'))
		  ->setSubject($this->form->getValue('subject'))
		  ->setBody($this->getPartial('send'));
 
             $this->getMailer()->send($mensagem);

             $this->getUser()->setFlash('notice', sfConfig::get('app_contact_form_notice'));
             $this->redirect('@contact');
             else:
             $this->getUser()->setFlash('error', sfConfig::get('app_contact_form_captcha'));
     
             endif;

          else:
             $this->getUser()->setFlash('error', sfConfig::get('app_contact_form_error'));
          endif;

      endif;



   }

   public function executeImage()
   
   {

    $font = '/mnt/xuflus/Webs/solar/plugins/sfContactFormPlugin/modules/contact/lib/monofont.ttf';
    $width = 100;
    $height = 40;
    $characters = 6;
    $possible = '23456789bcdfghjkmnpqrstvwxyz';
    $font_size = $height * 0.75;
    $code = '';
    $i = 0;

    while ($i < $characters) { 
	$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
	  $i++;
        
    }

    $this->getUser()->setAttribute('security_code', $code);

    $image = imagecreate($width, $height);

    $background_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 20, 40, 100);
    $noise_color = imagecolorallocate($image, 100, 180, 240);

      for( $i=0; $i<($width*$height)/3; $i++ ) {
         imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
      }
		
      for( $i=0; $i<($width*$height)/150; $i++ ) {
	imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
      }

      $textbox = imagettfbbox($font_size, 0, $font, $code);
      $x = ($width - $textbox[4])/2;
      $y = ($height - $textbox[5])/2;
      imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code);

    header("Content-type:  image/jpeg");
    imagepng($image);
    imagedestroy($image);
    
   }
}
