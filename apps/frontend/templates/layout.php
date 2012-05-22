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
</head>
<body>

  <div id='head'>
     <div id='texto'>
    <a href='<?php echo url_for('index/index'); ?>'>
      <img src="/images/texto1.png" alt="Solar Physics Group" />
    </a>
     </div>
    <div id='uib'>
      <div><a href='http://www.uib.es' ><img src='/images/uib.png' alt='UIB' /></a></div>
    </div>
  </div>
  <br/>
  <div id='menu'>
    <div class='item-menu'>
      <?php echo link_to('Index', 'index/index'); ?>
    </div>
    <div class='item-menu'>
      <?php echo link_to('People', 'people/index'); ?>
    </div>
    <div class='item-menu'>
      <?php echo link_to('Research Lines', 'researchline/index'); ?>
    </div>
    <div class='item-menu'>
      <?php echo link_to('News', 'new/index'); ?>
    </div>
    <?php
    $links = Doctrine_Core::getTable('SolarPage')
      ->createQuery('a')
      ->where('is_onmenu=1')
      ->execute();
    ?>
    <?php foreach ($links as $link): ?>
    <div class='item-menu'>
      <a href="<?php echo url_for('page/show?id='.$link->getId()) ?>">
        <?php echo $link->getMenuTitle() ?>
      </a>
    </div>
    <?php endforeach; ?>
    
    
    <div class='item-menu-admin'>
      <a href="/backend.php">Admin</a>
    </div>
    <div class='clear'></div>
  </div>
  <div id='content' class='transparent'>
    <?php echo $sf_content ?>
  </div>

  <div id='footer'>
    <a href='http://solar.uib.es'>Solar Physics Group</a> &#60; <a href='http://www.uib.es/depart/dfs/'>Physics department</a> &#60; <a href='http://www.uib.es'>UIB</a> 
    <!-- 		| Web creada por: <a href='http://www.eduherraiz.com'>Edu Herraiz</a> -->
  </div>

</body>
</html>
