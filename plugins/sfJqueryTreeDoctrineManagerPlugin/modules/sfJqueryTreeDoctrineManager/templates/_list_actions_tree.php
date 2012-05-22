<ul class="sf_admin_actions">
	<?php if($hasManyRoots):?>
 		<li class="sf_admin_action_list">
		<?php echo link_to( __('Back to root list') ,$sf_request->getParameter('module') . '/' . $sf_request->getParameter('action') );?>
	<?php endif;?>
	<li class="sf_admin_action_insert_node">
		<button disabled="disabled" class="nodeinteraction createnode">
        <img alt="" src="/sfJqueryTreeDoctrineManagerPlugin/images/node-insert-next.png"/><?php echo __('Insert Node');?>
    </button>
	</li>
	<li class="sf_admin_action_delete_node">
	<button disabled="disabled" class="nodeinteraction deletenode">
      <img alt="" src="/sfJqueryTreeDoctrineManagerPlugin/images/node-delete-next.png"/><?php echo __('Delete Node');?>
  </button>
	</li>
</ul>

<?php echo javascript_tag();?>
    $(document).ready(function(){

    $('.createnode').click(function(e){
        var t = $.tree.focused(); 
        if(t.selected) {
            sfJqueryTreeDoctrineManagerPluginCreateNew<?php echo $model;?> = true;
            t.create();
        } 
        else {
            alert("Select a node first");
        }
    });
                
    $('.deletenode').click(function(e){
        var t = $.tree.focused(); 
        if(t.selected) {
           if ( t.parent(t.selected) == -1){
            alert("<?php echo __('forbidden to remove root node');?>")
           }else{
            t.remove();
            }
        } 
        else {
            alert("Select a node first");
        }
    });

    })
<?php echo end_javascript_tag();?>