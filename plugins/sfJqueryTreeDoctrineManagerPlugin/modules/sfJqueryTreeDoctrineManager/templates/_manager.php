<?php use_helper('I18N', 'Date', 'JavascriptBase') ?>
<?php include_partial('sfJqueryTreeDoctrineManager/assets') ?>
<div id="sf_admin_container">
    <h1><?php echo sfInflector::humanize(sfInflector::underscore($model)); ?> Nested Set Manager</h1>
    <?php include_partial('sfJqueryTreeDoctrineManager/flashes') ?>
		<?php if ($hasManyRoots && !$sf_request->hasParameter('root') ):?>
			<?php include_partial('sfJqueryTreeDoctrineManager/manager_roots', array('model' => $model, 'field' => $field, 'root' => $root, 'roots' => $roots)) ?>
		<?php else: ?>
			<?php include_partial('sfJqueryTreeDoctrineManager/manager_tree', array('model' => $model, 'field' => $field, 'root' => $root, 'records' => $records, 'hasManyRoots' => $hasManyRoots)) ?>
		<?php endif;?>	
</div>



