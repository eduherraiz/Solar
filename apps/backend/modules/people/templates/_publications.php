<?php 

$query = Doctrine_Query::create()
  ->from('SolarPublication p')
  ->whereIn('p.people_id', $solar_people->id);

$result = $query->count();  
echo $result;
//echo image_tag(sprintf("/uploads/photospeople/thumbs/%s", $solar_people->photo)) ?>
