<?php 
  $last_category = 0;
  
  foreach ($solar_peoples as $solar_people): 

    $cat = $solar_people->getCategoryId();
    if($cat != $last_category){
      echo "<div class='headerlist'>".$solar_categories[$cat-1]."</div>";
      $last_category = $cat;
    }
  
  ?>

   <div id='peoplerow'>
     
     <div class='peoplerow-img'>
       <a href="<?php echo url_for('people/show?id='.$solar_people->getId()) ?>">
        <?php include_partial('photo', array('solar_people' => $solar_people, 'size' => 'thumbs')) ?>
      </a>
     </div>
     
     <div class='peoplerow-text'>
       <a href="<?php echo url_for('people/show?id='.$solar_people->getId()) ?>" class='peoplerow-name'>
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
     <div class='peoplerow-functions'>
       <a href='mailto:<?php echo $solar_people->getEmail() ?>'><?php echo $solar_people->getEmail() ?></a>
       <br/>
      Tel: <?php echo $solar_people->getPhone() ?><br/>
      Fax: <?php echo $solar_people->getFax() ?>
     </div>
     
     <div class='clear'></div>
     
   </div>
    
<?php endforeach; ?>
