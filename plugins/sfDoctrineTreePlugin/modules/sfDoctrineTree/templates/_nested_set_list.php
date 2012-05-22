<?php if (!function_exists('javascript_tag'))
        try {
            use_helper('Javascript');
        } catch (Exception $e) { }; 
    
    if (!function_exists('javascript_tag'))
        use_helper('JavascriptBase');
?>
<?php use_helper('DoctrineTree'); ?>
<?php if( isset($records) && is_object($records) && $records->count() > 0 ): ?>
  <ul  id="dhtmlgoodies_tree2" class="dhtmlgoodies_tree">
  <?php $prevLevel = 0;  
  $noRootAttr =  " noDrag='true' noSiblings='true' noDelete='true'";
  if ($no_root_rename) $noRootAttr .= " noRename='true'";
  if (isset($options['noAdd']) && $options['noAdd']) $noRootAttr .= " noAdd='true'";
  if (is_object($options) && get_class($options) == 'sfOutputEscaperArrayDecorator')
    $options = $options->getRawValue();
    $nodeNoAttr = _tag_options($options); ?>
  <?php foreach($records AS $record):
  $noAttr = $record->getNode()->getLevel() == 0 ?  $noRootAttr : $nodeNoAttr;
  $identifier = $record->identifier();
  if (is_object($identifier) && get_class($identifier) == 'sfOutputEscaperArrayDecorator')
    $identifier = $identifier->getRawValue();
  $identifier = array_values($identifier); ?>
  <?php if($prevLevel > 0 && $record->getNode()->getLevel() == $prevLevel)  echo '</li>'; ?>  
  <?php if($record->getNode()->getLevel() > $prevLevel)  echo '<ul>'; elseif ($record->getNode()->getLevel() < $prevLevel) echo str_repeat('</ul></li>', $prevLevel - $record->getNode()->getLevel()); ?>
  <li id ="node<?php echo $identifier[0] ?>" <?php echo $noAttr ?>>
  <?php $partial = $link_partial ? $link_partial : 'sfDoctrineTree/link'; ?>
  <?php include_partial($partial, array('record' => $record, 'model' => $model, 'field' => $field, 'root' => $root, 'identifier' => $identifier[0])) ?>
  <?php if ($record->getNode()->getLevel() == 0) echo image_tag('/sfDoctrineTreePlugin/images/indicator.gif', array('style' => 'padding:0pt 5px;display:none;vertical-align:middle;', 'id' => 'doctrine_tree_indicator')) ?>
  <?php $prevLevel = $record->getNode()->getLevel();
  endforeach; ?>
  </li></ul>
<?php endif; ?>
