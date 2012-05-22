<?php $solar_researchline = $solar_researchline->getRawValue(); ?>  

<h2>
  <a href="<?php echo url_for('researchline/show?id='.$solar_researchline->getId()) ?>">
    <?php echo $solar_researchline->getTitle() ?>
  </a>
</h2>

<div id="researchline-text">
  <p><?php echo $solar_researchline->getComplete() ?></p>
  
  <h4>People working in this research line</h4>

    <?php 
    $people =  $solar_researchline->getSolarPeoples();
    foreach ($people as $pp): 
      
      echo "<a href='".url_for('people/show?id='.$pp->getId())."'>";
      
      if($pp->getNameWeb()){
        echo $pp->getNameWeb()."<br/>";
      }else{
        echo $pp->getName()." ". $pp->getSurname()."<br/>";
      }
    
      echo "</a>";
      
    endforeach;
    ?>
  
</div>

<div id="researchline-img">
  <?php include_partial('rlimg', array('image' => $solar_researchline->getImage())) ?>
</div>

<div class='clear'></div>