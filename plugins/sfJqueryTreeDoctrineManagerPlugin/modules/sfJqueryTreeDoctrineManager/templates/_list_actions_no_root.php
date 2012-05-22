<div>
    <form id="<?php echo strtolower($model);?>_root_create" action="<?php echo url_for('sfJqueryTreeDoctrineManager/Add_root');?>" method="post">
        <label for="<?php echo strtolower($model);?>_<?php echo $field;?>"><?php echo ucfirst($field);?> : </label>
        <input type="text" id="<?php echo strtolower($model);?>_<?php echo $field;?>" value="" name="<?php echo strtolower($model);?>[<?php echo $field;?>]"/>
        <input type="hidden" name="model" value="<?php echo $model;?>"/>
			  <button type="submit">
            <img class="actionImage" src="/sfJqueryTreeDoctrineManagerPlugin/images/node-insert-next.png"/><?php echo __('Create Root');?>
        </button>
    </form>
</div>

<?php echo javascript_tag();?>
$(document).ready(function(e){
    $('#<?php echo strtolower($model);?>_root_create').submit(function(e){
        e.preventDefault();
        var src = $(this).find('.actionImage').attr('src');
        $(this).find('.actionImage').attr('src', '/sfJqueryTreeDoctrineManagerPlugin/css/throbber.gif');
        $.post( $(this).attr('action'), $(this).serialize(), function(){
            document.location.reload();
        } );
    });
});
<?php echo end_javascript_tag();?>