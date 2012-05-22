<?php
/*
 * This file is part of the isicsWidgetFormTinyMCEPlugin package.
 * Copyright (c) 2008 ISICS.fr <contact@isics.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * TinyMCE Widget
 *
 * @package isicsWidgetFormTinyMCEPlugin
 * @author Nicolas CHARLOT <nicolas.charlot@isics.fr>
 **/
class isicsWidgetFormTinyMCE extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *  * tiny_options          : Associative array of Tiny MCE options (empty array by default)
   *  * options_without_quotes: Options without quotes (only "setup" by default)
   *  * with_gzip             : Enables GZip compression (false by default)
   *  * tiny_gz_options       : Associative array of Tiny MCE Compressor options (empty array by default)
   *
   * @see sfWidgetFormTextarea
   **/    
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('tiny_options', sfConfig::get('app_tiny_mce_default', array()));
    $this->addOption('options_without_quotes', sfConfig::get('app_tiny_mce_options_without_quotes', array('setup')));    
    $this->addOption('with_gzip', false);    
    $this->addOption('tiny_gz_options', sfConfig::get('app_tiny_mce_gz_default', array()));
  }
  
  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @see sfWidget
   **/    
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $javascript = 'tiny_mce/tiny_mce'.($this->getOption('with_gzip') ? '_gzip' : '');
    sfContext::getInstance()->getResponse()->addJavascript($javascript);

    $options = '';
    foreach ($this->getOption('tiny_options') as $key => $option)
    {
    	$options .= ",\n    ".$key.': '.(in_array($key, $this->getOption('options_without_quotes')) ? $option : '\''.$option.'\'');
    }
    
    $id = $this->generateId($name, $value);

   
    $script_content = <<<JS
//<![CDATA[
   tinyMCE.init({
    mode: "exact",
    theme: "advanced",
    relative_urls: false,
    editor_selector : "rich",
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor,hr,removeformat,visualaid,|,sub,sup,|,charmap",
    theme_advanced_buttons3 : "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    file_browser_callback : 'sfAssetsLibrary.fileBrowserCallBack',
    elements: '{$id}'{$options}
  });
//]]>
JS;

    if ($this->getOption('with_gzip'))
    {
      $gz_options = '';
      foreach ($this->getOption('tiny_gz_options') as $key => $option)
      {
        $gz_options .= "\n    ".$key.': \''.$option.'\',';
      }
      
      $script_gzip_content = <<<JS
//<![CDATA[
  tinyMCE_GZ.init({{$gz_options}
    disk_cache: true,
    debug: false
  });
//]]>
JS;
    }
    
    return parent::render($name, $value, $attributes, $errors)
      .($this->getOption('with_gzip') ? $this->renderContentTag('script', $script_gzip_content, array('type' => 'text/javascript')): '')
      .$this->renderContentTag('script', $script_content, array('type' => 'text/javascript'));
  }
}