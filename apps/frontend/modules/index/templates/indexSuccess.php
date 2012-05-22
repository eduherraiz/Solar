<?php $solar_index = $solar_index->getRawValue() ?>
<?php $solar_researchlines = $solar_researchlines->getRawValue(); ?>  
<?php $solar_news = $solar_news->getRawValue(); ?>

<div id="index-text">
  <?php echo $solar_index->getTextPage() ?>
</div>

<div id="index-news">
  <div class="index-news-title">
    <?php echo link_to('News', 'new/index'); ?>
  </div>
  <div class="index-news-container">
    <?php foreach ($solar_news as $solar_new): ?>
    <a href="<?php echo url_for('new/show?id='.$solar_new->getId()) ?>">
        <?php echo $solar_new->getTitle() ?>
    </a>
    <div class="index-news-date">at <?php echo $solar_new->getUpdatedAt() ?></div>
    <?php endforeach; ?>
  </div>
</div>
<div id="index-people">
<div class="index-news-title">
  <?php echo link_to('People', 'people/index'); ?>
</div>
     <?php  
  $last_category = 0;
  
  foreach ($solar_peoples as $solar_people): 

    $cat = $solar_people->getCategoryId();
    if($cat != $last_category){
//      echo "<div class='headerlist'>".$solar_categories[$cat-1]."</div>";
      $last_category = $cat;
    }
  
  ?>

   <div class='index-peoplerow'>
     
     <div class='peoplerow-img'>
       <a href="<?php echo url_for('people/show?id='.$solar_people->getId()) ?>">
        <?php include_partial('photo', array('solar_people' => $solar_people, 'size' => 'thumbs')) ?>
      </a>
     </div>
     
     <div class='index-peoplerow-text'>
       <a href="<?php echo url_for('people/show?id='.$solar_people->getId()) ?>" class='index-peoplerow-name'>
          <?php 
            $treatment = array('Sr.', 'Dr.', 'Pr.');
            echo $treatment[$solar_people->getTreatment()]." ";
            
            if ($solar_people->getNameWeb())
            {
              echo $solar_people->getNameWeb();
            }else{
              echo $solar_people->getName()." ".$solar_people->getSurname();
            }
          ?>
        </a>
     </div>
<!--     <div class='peoplerow-functions'>
       <a href='mailto:<?php echo $solar_people->getEmail() ?>'><?php echo $solar_people->getEmail() ?></a>
       <br/>
      Tel: <?php echo $solar_people->getPhone() ?><br/>
      Fax: <?php echo $solar_people->getFax() ?>
     </div>-->
     
     <div class='clear'></div>
     
   </div>
    
<?php endforeach; ?>

  
</div>

<div class="clear"></div>

<div id="index-research-lines">
  <div class="index-news-title">
    <?php echo link_to('Research Lines', 'researchline/index'); ?>
  </div>

  <?php foreach ($solar_researchlines as $solar_researchline): ?>
     <div class="index-researchline-img">
      <?php include_partial('rlimg', array('image' => $solar_researchline->getImage())) ?>
     </div>
    <div class="index-researchline-div">
      <a href="<?php echo url_for('researchline/show?id='.$solar_researchline->getId()) ?>" class="index-researchline-title">
       <?php echo $solar_researchline->getTitle() ?>
       </a>
      <div class="index-researchline-abstract">
        <?php echo $solar_researchline->getAbstract() ?>
      </div>
     </div>

      <div class='clear'></div>
  <?php endforeach; ?>
</div>



<div class="clear"></div>