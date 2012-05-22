<?php
    
    function get_nested_set_manager($model, $field, $root = 0){
        
        if ( !sfConfig::has('app_sfJqueryTree_withContextMenu') ){
            sfConfig::set('app_sfJqueryTree_withContextMenu',true);
        }
    
        sfContext::getInstance()->getResponse()->addStylesheet('/sfJqueryTreeDoctrineManagerPlugin/jsTree/themes/default/style.css');
        sfContext::getInstance()->getResponse()->addStylesheet('/sfJqueryTreeDoctrineManagerPlugin/css/screen.css');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/jquery.js');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/jquery.cookie.js');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/jquery.tree.min.js');
        sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/plugins/jquery.tree.cookie.js');
        
        if ( sfConfig::get('app_sfJqueryTree_withContextMenu') ){
            sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryTreeDoctrineManagerPlugin/jsTree/plugins/jquery.tree.contextmenu.js');
        }
        
        return get_component('sfJqueryTreeDoctrineManager', 'manager', array('model' => $model, 'field' => $field, 'root' => $root));
    }