<?php $solar_page = $solar_page->getRawValue(); ?>

<?php if($solar_page->getPassword() AND ($solar_page->getPassword() != $pass )) { ?>
    <form action="" method="POST">
        Password: <input type='password' id='password' name="password" />
    </form>
<?php }else{ ?>
    
  

<a href="<?php echo url_for('page/show?id='.$solar_page->getId()) ?>" class="new-item-head">
  <?php echo $solar_page->getTitle() ?>
</a>
<br/>
  
<?php echo $solar_page->getUpdatedAt() ?>
<?php echo $solar_page->getTextPage() ?>


<?php } ?>
