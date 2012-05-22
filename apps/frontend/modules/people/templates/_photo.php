<?php 
  if(!$size)
    $size = 'thumb';
  
  echo image_tag(sprintf("/uploads/photospeople/%s/%s", $size, $solar_people->photo)) 
 ?>
