<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
<title>
  <?php if (!include_slot('title')): ?>
    Solar Physics Group
  <?php endif; ?>
</title>

  <link rel="shortcut icon" href="/images/icon.gif" />
  
  <?php include_stylesheets() ?>
  <?php include_javascripts() ?>
  <?php use_helper('sfAsset') ?>
  
<!-- Skin CSS file -->
</head>
<body>
  <div id='head'>
     <div id='texto'>
	<a href='/'><img src="/images/texto1.png" alt="Solar Physics Group" /></a>
     </div>
    <div id='uib'>
      <div><a href='http://www.uib.es'><img src='/images/uib.png' alt="UIB" /></a></div>
    </div>
  </div>

  <div id='menu'>
    <?php if ($sf_user->isAuthenticated()): ?>
      <div class='item-menu'>
        <?php echo link_to('Index', 'index/edit?id=1') ?>
      </div>

      <div class='item-menu'>
        <?php echo link_to('Users', 'sf_guard_user') ?>
      </div>
      <div class='item-menu'>
        <?php echo link_to('People', 'people/index'); ?>
      </div>
      <div class='item-menu'>
        <?php echo link_to('Categories', 'category/index'); ?>
      </div>
      <div class='item-menu'>
        <?php echo link_to('Research Lines', 'researchline/index'); ?>
      </div>
      <div class='item-menu'>
        <?php echo link_to('Pages', 'page/index'); ?>
      </div>
      <div class='item-menu'>
        <?php echo link_to('News', 'new/index'); ?>
      </div>
      <div class='item-menu'>
        <?php echo link_to('Media', 'lyMediaAsset/icons'); ?>
      </div>
      <div class='clear'></div>
    <?php endif ?>
  </div>
  
  <div id='content' class='transparent'>
    <div id='admin'>
    Administration panel <?php if ($sf_user->isAuthenticated()): ?>(<?php echo $sf_user->getUsername(); ?>) - <?php echo link_to('Logout', 'sf_guard_signout') ?><?php  endif ?>
    </div>
      <?php echo $sf_content ?>
  </div>

  <div id='footer'>
    <a href='http://solar.uib.es'>Solar Physics Group</a> < <a href='http://www.uib.es/depart/dfs/'>Physics department</a> < <a href='http://www.uib.es'>UIB</a> 
    <!-- 		| Web creada por: <a href='http://www.eduherraiz.com'>Edu Herraiz</a> -->
  </div>

</body>
</html>
