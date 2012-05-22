<?php $solar_researchlines = $solar_researchlines->getRawValue(); ?>  

<h1>Research Lines</h1>

  <?php foreach ($solar_researchlines as $solar_researchline): ?>

      <h2>
        <a href="<?php echo url_for('researchline/show?id='.$solar_researchline->getId()) ?>">
          <?php echo $solar_researchline->getTitle() ?>
        </a>
      </h2>
      
     <div id="researchline-text">
        <p><?php echo $solar_researchline->getAbstract() ?></p>
        <a href="<?php echo url_for('researchline/show?id='.$solar_researchline->getId()) ?>">More...</a>
     </div>
     <div id="researchline-img">
      <?php include_partial('rlimg', array('image' => $solar_researchline->getImage())) ?>
      </div>
      <div class='clear'></div>
  <?php endforeach; ?>


