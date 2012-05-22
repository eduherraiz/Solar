<?php
function get_doctrine_tree($model, $field, $root = 0, $options = array(), $query = null)
{
  sfContext::getInstance()->getResponse()->addStylesheet('/sfDoctrineTreePlugin/css/drag-drop-folder-tree');
  sfContext::getInstance()->getResponse()->addJavascript(sfConfig::get('sf_prototype_web_dir'). '/js/prototype');
  sfContext::getInstance()->getResponse()->addJavascript('/sfDoctrineTreePlugin/js/context-menu');
  sfContext::getInstance()->getResponse()->addJavascript('/sfDoctrineTreePlugin/js/drag-drop-folder-tree');
  
  $rename_url = sfConfig::get('app_doctrine_tree_rename_url', 'sfDoctrineTree/rename');
  $delete_url = sfConfig::get('app_doctrine_tree_delete_url', 'sfDoctrineTree/delete');
  $add_child_url = sfConfig::get('app_doctrine_tree_add_child_url', 'sfDoctrineTree/add_child');
  $save_tree_url = sfConfig::get('app_doctrine_tree_save_tree_url', 'sfDoctrineTree/save_tree');
  
  $link_partial = _get_option($options, 'link_partial');
  $no_help = _get_option($options, 'no_help');
  if (!$max_depth = _get_option($options, 'max_depth'))
    $max_depth = 6;
  $no_root_rename = _get_option($options, 'no_root_rename');
  
  return get_component('sfDoctrineTree', 'tree',
    array('model' => $model, 'field' => $field, 'root' => $root, 'link_partial' => $link_partial, 'no_root_rename' => $no_root_rename, 'no_help' => $no_help, 'options' => $options, 'query' => $query))
    . javascript_tag("document.observe('dom:loaded', function() {treeObj = new JSDragDropTree();
treeObj.setTreeId('dhtmlgoodies_tree2');
treeObj.setMaximumDepth($max_depth);
treeObj.setMessageMaximumDepthReached('Maximum depth reached');
treeObj.setRootId('$root');
treeObj.setNameField('$field');
treeObj.setModel('$model');
treeObj.filePathRenameItem = '" . url_for($rename_url, true) . "';
treeObj.filePathDeleteItem = '" . url_for($delete_url, true) . "';
treeObj.filePathAddItem = '" . url_for($add_child_url, true) . "';
treeObj.filePathSaveTree = '" . url_for($save_tree_url, true) . "';
treeObj.linkPartial = '" . ($link_partial ? $link_partial : '') . "';
treeObj.initTree();
treeObj.expand(1);})");
}

function include_doctrine_tree($model, $field, $root = 0, $options = array(), $query = null)
{
  echo get_doctrine_tree($model, $field, $root, $options, $query);
}