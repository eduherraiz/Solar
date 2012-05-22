<?php $solar_new = $solar_new->getRawValue(); ?>
  

<a href="<?php echo url_for('new/show?id='.$solar_new->getId()) ?>" class="new-item-head">
  <?php echo $solar_new->getTitle() ?>
</a>
<br/>
  
<?php echo $solar_new->getUpdatedAt() ?>
<?php echo $solar_new->getTextPage() ?>

<br/><br/>
<a href="<?php echo url_for('new/index') ?>">Click here to see all news</a>
