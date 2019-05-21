<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$title = 'elect_image';
if (isset($_GET["id"]) && !empty($_GET["id"])) {
  $correctimage= TRUE;
  $image_id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
}
if (isset($_POST["pricerange"]) && isset($_POST["submit"])) {
  $price_range = filter_input(INPUT_POST, 'pricerange', FILTER_VALIDATE_INT);
  $price_range = trim($price_range);
  $sql="INSERT INTO electronic_price_range(elec_image_id,price_range_id) VALUES(:id,:price_id)";
  $params = array(':id' => $image_id,'price_id' => $price_range);
  $add_price_furniture= exec_sql_query($db, $sql, $params);
  if ($add_price_furniture){
    array_push($notify, "Sucess!");
  }
  else{
    array_push($notify, "You do not add the price range successfully!");
  }
}
if(isset($_POST["Existed_tags_delete"])&& isset($_POST["Delete_Tags"])){
  $delete_price_range = filter_input(INPUT_POST, "Existed_tags_delete", FILTER_VALIDATE_INT);
  $delete_price_range = trim($delete_price_range);
  $sql = "DELETE FROM electronic_price_range WHERE electronic_price_range.elec_image_id = :img_id AND electronic_price_range.price_range_id = :price_id";
  $params = array(
    ":img_id" => $image_id,
    ":price_id" => $delete_price_range);
  $result = exec_sql_query($db, $sql, $params);
  if ($result){
    array_push($notify, "Success!");
    }
  else{
    array_push($notify, "You do not delete the price range successfully");
  }
}
if(isset($_POST["remove_type"])&& isset($_POST["delete_type"])){
  $tag_id_to_delete = filter_input(INPUT_POST, 'remove_type', FILTER_SANITIZE_STRING);
  $sql = "DELETE FROM electronic_types WHERE electronic_types.elec_image_id = :img_id AND electronic_types.elec_type_id = :type_id";
  $params = array(
    ":img_id" => $image_id,
    ":type_id" => $tag_id_to_delete);
  $result = exec_sql_query($db, $sql, $params);
  if ($result){
    array_push($notify, "Success!");
    header("Refresh:0");
    }
  else{
    array_push($notify, "Something went wrong :(");
  }
}
if(isset($_POST["add_type"])&& isset($_POST["new_type"])){
  $tag_id_to_add = filter_input(INPUT_POST, 'add_type', FILTER_SANITIZE_STRING);
  $sql = "INSERT INTO electronic_types (elec_image_id, elec_type_id) VALUES (:img_id, :type_id)";
  $params = array(
    ":img_id" => $image_id,
    ":type_id" => $tag_id_to_add);
  $result = exec_sql_query($db, $sql, $params);
  if ($result){
    array_push($notify, "Success!");
    header("Refresh:0");
    }
  else{
    array_push($notify, "Something went wrong :(");
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css"/>
  <title>Electronics</title>
</head>
<body>
<header>
<?php include("includes/navigation.php");?>

</header>

<h2 class = "headers"> Electronics for Sale</h2>

<section class="content">

<?php

// Display Single Image
    if (isset($_GET['id'])) {
      $image_id = $_GET['id'];
      $sql = "SELECT DISTINCT * FROM electonic_images WHERE electonic_images.id=$image_id";
      $image = exec_sql_query($db, $sql)->fetchAll(PDO::FETCH_ASSOC)[0];
        echo '<div class="single_formatting">
        <figure class="big_figure">
        <img class="big_image" src="uploads/elect_image/'. $image['id'] . "." . $image['file_ext']. '"/>

        <figcaption class="big_caption"><div class="image_label"><strong>Name:</strong></div>'.$image['name'].'
         <br> <div class="image_label"><strong>Price:</strong></div>'. $image['price']. '<br> <div class="image_label"><strong>Descripton:</strong></div>'. $image['description'] . '</figcaption></figure></div>';
         // Images taken by Nigel (client)

    }

if(is_user_logged_in()){
  ?>

<h2>Delete The Price Range For This Electronic</h2>
  <?php echonotify($notify)?>
  <fieldset>
              <legend>Delete Price range</legend>
            <form action="elect_image.php?id=<?php echo $image_id; ?>" method="post">
              <label>Delete Existing Price Range:</label> <select name="Existed_tags_delete" required>
                       <option value="" selected disabled>Select the price range</option>
                        <?php
                          // Populate dropdown with the tags that are attached to image
                          $sql =  "SELECT price_range.id,price_range.price_range
                          FROM price_range JOIN electronic_price_range ON price_range.id=electronic_price_range.price_range_id
                          WHERE electronic_price_range.elec_image_id=:id";
                          $records = exec_sql_query($db, $sql, array(':id' => $image_id))->fetchAll(PDO::FETCH_ASSOC);
                          foreach($records as $record) {
                            echo "<option value='" . $record["id"] . "'>" . $record["price_range"] . "</option>";
                          }
                        ?>
                       </select>
               <button name="Delete_Tags" type="submit" >Delete Tag</button>
            </form>
                        </fieldset>
  <?php
}
if(is_user_logged_in()){
  ?>
   <h2>Add A Price Range For This Electronic</h2>
  <?php echonotify($notify)?>
  <fieldset>
    <legend>Add a Price range if you don't have one</legend>
   <form id="priceaddForm" action="elect_image.php?id=<?php echo $image_id; ?>" method="post">
   <label>Choose a Price Range</label>
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
      <button name="submit" type="submit">Add price range</button>
    </form>
  </fieldset>

<?php
}
if(is_user_logged_in()){
  ?>
  <h2>Delete Electronic Type For This Electronic</h2>
  <?php echonotify($notify)?>
  <fieldset>
              <legend>Delete an existing Electronics type</legend>
            <form action="elect_image.php?id=<?php echo $image_id; ?>" method="post">
              <label>Select a Electronics type:</label> <select name="remove_type" required>
                       <option value="" selected disabled>Select the type</option>
                        <?php
                          // Populate dropdown with the tags that are attached to image
                          $sql =  "SELECT * FROM electronic_types INNER JOIN types_for_electronics ON electronic_types.elec_type_id = types_for_electronics.id WHERE electronic_types.elec_image_id=:id";
                          $params = [
                            ':id' => $image_id
                          ];

                          $records = exec_sql_query($db, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
                          foreach($records as $record) {
                            echo "<option value='" . $record["id"] . "'>" . $record["type"] . "</option>";
                          }
                        ?>
                       </select>
               <button name="delete_type" type="submit" >Delete Type</button>
            </form>
                        </fieldset>

<?php
} if(is_user_logged_in()) { ?>
  <h2>Add A Electronic Type For This Electronic</h2>
  <?php echonotify($notify)?>
  <fieldset>
              <legend>Add Electronics type</legend>
            <form action="elect_image.php?id=<?php echo $image_id; ?>" method="post">
              <label>Select a Electronics type:</label> <select name="add_type" required>
                       <option value="" selected disabled>Select the type</option>
                        <?php
                          $sql = "SELECT * FROM types_for_electronics";
                          $records = exec_sql_query($db, $sql)->fetchAll(PDO::FETCH_ASSOC);
                          foreach($records as $record) {
                            echo "<option value='" . $record["id"] . "'>" . $record["type"] . "</option>";
                          }
                        ?>
                       </select>
               <button name="new_type" type="submit" >Add type</button>
            </form>
                        </fieldset> <?php
}
?>

<?php
    //form for the adding of images
if (!is_null($current_user)) { ?>

<h2>Delete Entire Image</h2>
<fieldset>

<form id="delete-image-form" action="electronics.php" method="post">
  <button name="delete_image_elect" value="<?php echo $image_id; ?>" type = "submit">Delete Image</button>
</form>

</fieldset>
<?php
}
?>

</section>

<?php include("includes/footer.php");?>
</body>
</html>
