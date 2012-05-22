<?php if (!function_exists('javascript_tag'))
        try {
            use_helper('Javascript');
        } catch (Exception $e) { }; 
    
    if (!function_exists('javascript_tag'))
        use_helper('JavascriptBase');
?>
<div class="doctrine_tree_container" id="<?php echo $model; ?>_doctrine_tree_container">
  <div id='doctrine_tree_links'><?php echo link_to_function('Collapse all', 'treeObj.collapseAll();') ?>&nbsp;&nbsp;&nbsp;
  <?php echo link_to_function('Expand all', 'treeObj.expandAll();') ?>
  <?php if (!isset($options['noDrag']) || !$options['noDrag']) echo '&nbsp;&nbsp;&nbsp;' . link_to_function('Save tree', 'treeObj.saveTree();') ?>
  <?php if (!$no_help) echo '&nbsp;&nbsp;&nbsp;' . link_to_function('Help', "$('doctrine_tree_help').toggle();") ?></div>
  <?php if (!$no_help) include_partial('sfDoctrineTree/help'); ?>
  <?php include_partial('sfDoctrineTree/nested_set_list', array('model' => $model, 'field' => $field, 'root' => $root, 'link_partial' => $link_partial, 'no_root_rename' => $no_root_rename, 'options' => $options, 'records' => $records)); ?>
</div>