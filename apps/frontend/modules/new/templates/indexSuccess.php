<?php $solar_news = $solar_news->getRawValue(); ?>
  
<?php foreach ($solar_news as $solar_new): ?>

<div id="new-item">
<a href="<?php echo url_for('new/show?id='.$solar_new->getId()) ?>" class="new-item-head">
  <?php echo $solar_new->getTitle() ?>
</a>
<br/>
<span class="new-date"><?php echo $solar_new->getUpdatedAt() ?></span>
<?php echo $solar_new->getTextPage() ?>
</div>
<?php endforeach; ?>
