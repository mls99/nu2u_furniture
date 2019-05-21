<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$title = 'Elect_Album';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css"/>

  <title>Albums</title>
</head>
<body>
<header>
<?php include("includes/navigation.php");

if ( isset($_GET['pricerange']) && isset($_GET['submit']) ){
  $do_search = TRUE;
  $price_range = filter_input(INPUT_GET, 'pricerange', FILTER_VALIDATE_INT);
  $price_range = trim($price_range);
}else{
  $do_search = False;
  $price_range=NULL;
}
?>
</header>

<h2 class = "headers"> Electronic for Sale</h2>
<form id="searchForm" method="get">
      <label class="choose">Choose a Price Range</label>
      <div class="pricerange">
      <select name="pricerange">

      <option value="" selected disabled>Select a Price Range</option>
                     <?php
                       // Populate dropdown with already existing tags
                       $records = exec_sql_query($db, "SELECT * FROM price_range", array())->fetchAll(PDO::FETCH_ASSOC);
                       foreach($records as $record) {
                           echo "<option value='" . $record['id'] . "'>" . $record['price_range'] . "</option>";
                       }
                     ?>
      </select>
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']);?>">

      <button name="submit" type="submit">Search</button>
                      </div>
    </form>
<?php

if (isset($_GET['id'])) {
  $album_id = $_GET['id'];
  if ($do_search){
  $sql = "SELECT * FROM electonic_images INNER JOIN electronic_types ON electronic_types.elec_image_id = electonic_images.id INNER JOIN types_for_electronics ON types_for_electronics.id =  electronic_types.elec_type_id INNER JOIN electronic_price_range ON electonic_images.id=electronic_price_range.elec_image_id
  JOIN price_range ON electronic_price_range.price_range_id=price_range.id
  Where types_for_electronics.id=$album_id AND price_range.id=$price_range";
  } else {
    $sql = "SELECT * FROM electonic_images INNER JOIN electronic_types ON electronic_types.elec_image_id = electonic_images.id INNER JOIN types_for_electronics ON types_for_electronics.id = electronic_types.elec_type_id
      WHERE types_for_electronics.id=$album_id";
  }
  $images = exec_sql_query($db, $sql)->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <div class="_album">
  <?php
  foreach($images as $image){

    echo "<figure>
                  <a href='elect_image.php?id=". $image["elec_image_id"] . "'>
                    <img class='album_photos' src='/uploads/elect_image/" . $image['elec_image_id'] . "." . $image['file_ext'] . "' alt='" . $image['file_name'] . "'>
                  </a>
                  </figure>";
                  // Images taken by Nigel (client)
  }
}
?>
</div>
<?php include("includes/footer.php");?>
</body>


</html>
