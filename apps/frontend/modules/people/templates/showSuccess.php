<?php $solar_people = $solar_people->getRawValue() ?>

<!--<a href="<?php echo url_for('people/index') ?>">List</a>-->

<div id='peopleshow'>
  <div id='peopleshow-name'>
  <?php 
    if ($solar_people->getNameWeb()){
      echo $solar_people->getNameWeb();
    }else{
      echo $solar_people->getName()." ".$solar_people->getSurname();
    }
  ?>
  </div>
  
  <div id='peopleshow-info'>
    <?php echo $solar_people->getAbstract(); ?>
  </div>
</div>

<div id='peopleshow-image'>
  <?php include_partial('photo', array('solar_people' => $solar_people, 'size' => 'normal')) ?>
    <br/><br/>
    <a href='mailto:<?php echo $solar_people->getEmail(); ?>'><?php echo $solar_people->getEmail(); ?></a><br/>
    <?php echo $solar_people->getAddress(); ?><br/>
    Phone: <?php echo $solar_people->getPhone(); ?><br/>
    Fax: <?php echo $solar_people->getFax(); ?>
    <?php 
      if ($solar_people->getWebsite()) 
        echo "Website: ".$solar_people->getWebsite();
      ?> 
</div>

<div class='clear'></div>
  
<div id='peopleshow-researchlines'>
  <h4>Research Lines</h4>
   <?php 
    $research_lines =  $solar_people->getSolarResearchlines();
    foreach ($research_lines as $rl):
      $link = url_for('researchline/show?id='.$rl->getId());
      echo "<a href='$link'>";
      echo $rl->getTitle()."<br/>";
      echo "</a>";
    endforeach;
    ?>
</div>

<h4>Publications</h4>

<div id='peopleshow-legend'>J = Journal, R = References, A = Article, P = Preprint</div>

<div id='peopleshow-publications'>
  <table>
    <tr>
      <th>Date</th>
      <th>Title</th>
      <th></th>
      <th></th>
      <th></th>
    </tr>
     <?php 
    $publications =  $solar_people->getSolarPublications();
    foreach ($publications as $pb): 
      echo "<tr>";
    
      echo "<td>".$pb->getDate()."</td>";
      $link = $pb->getUrl();
      $link2 = $pb->getJournal();
      $link3 = $pb->getReferencess();
      $link4 = $pb->getArticle();
      $link5 = $pb->getPreprint();
      
      echo "<td><a href='$link'>".$pb->getTitle()."</a></td>";
      
      if($link2)
        echo "<td><a href='$link2'>J</a></td>";
      else
        echo "<td></td>";

      if($link3)
        echo "<td><a href='$link3'>R</a></td>";
      else
        echo "<td></td>";

      if($link4)
        echo "<td><a href='$link4'>A</a></td>";
      else
        echo "<td></td>";

     if($link5)
        echo "<td><a href='$link5'>P</a></td>";
      else
        echo "<td></td>";

      
      echo "</tr>";
    endforeach;
    ?>
    </table>
</div>

