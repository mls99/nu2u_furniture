<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$title= 'Furniture';

// if user tried to upload a new image, process user input and upload image if valid

if (isset($_POST["submit_upload"])) {
  $upload_info = $_FILES["image_file"];
  $desc = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
  $basename = basename($upload_info["name"]);
  $name = filter_input(INPUT_POST, 'image_name', FILTER_SANITIZE_STRING);
  $file_ext = strtolower(filter_var(pathinfo($upload_info['name'])['extension'], FILTER_SANITIZE_STRING));
  $file_size = $upload_info['size'];
  $add_type = filter_input(INPUT_POST, 'add_type', FILTER_VALIDATE_INT);
  $add_price = filter_input(INPUT_POST, 'add_price', FILTER_VALIDATE_INT);
  $add_price_range=filter_input(INPUT_POST, 'pricerange', FILTER_VALIDATE_INT);

  if ($upload_info['error'] == 0) {
    //check if file has valid image file format
    if (in_array($file_ext, $image_formats)) {
      //Check if file is too large
      if ($file_size <= 2000000) {
        $sql = "INSERT INTO images (file_name, file_ext, name, description, price) VALUES (:fname, :ext, :name, :desc, :price)";
        $params = array(':fname' => $basename,
                        ':ext' => $file_ext,
                        ':name' => $name,
                        ':desc' => $desc,
                        ':price' => $add_price
                      );
        $result = exec_sql_query($db, $sql, $params);
        if ($result) {
          $image_id = $db->lastInsertId("id");
          $file_path = "uploads/images/". $image_id . "." . $file_ext;
          // Check if file was successfully uploaded to server
          if (move_uploaded_file($upload_info['tmp_name'], $file_path)){
            $sql = "INSERT INTO furniture_types (image_id, type_id) VALUES (:id, :type)";
            $params = array(':id' => $image_id,
                        ':type' => $add_type
                      );
            $result = exec_sql_query($db, $sql, $params);

            $sql = "INSERT INTO furniture_price_range (image_id, price_range_id) VALUES (:id, :range)";
            $params = array(':id' => $image_id,
                        ':range' => $add_price_range
                      );
            $results = exec_sql_query($db, $sql, $params);
            array_push($notify, " Successfully Upload Image!");
          }
        }
      }
    }
  }
}

// Delete image button was pressed by uploader, $_POST["delete_image"] contains deleted image ID
if (isset($_POST["delete_image"])) {
  // Make sure image ID submitted through delete image button is valid
  $image_id = filter_input(INPUT_POST, 'delete_image', FILTER_VALIDATE_INT);
  if (is_int($image_id)) {
    $params = array(':id' => $image_id);
    $records = exec_sql_query($db, "SELECT * FROM images WHERE id=:id", $params)->fetchAll(PDO::FETCH_ASSOC);
    if (!$records) {
      array_push($notify, "<p>Failed to delete image because image ID doesn't exist.</p>");
    }
  }
  else {
    array_push($notify, "<p>Failed to delete image because image ID doesn't exist.</p>");
  }

    $file_ext = $records[0]['file_ext'];
    $file_path = "uploads/images/". $image_id . "." . $file_ext;

    // Delete image from images database
    $sql = "DELETE FROM images WHERE id = :img_id";
    $params = array(':img_id' => $image_id);
    $result_images = exec_sql_query($db, $sql, $params);

    // Delete tags associated with deleted image in furniture types gallery
    $sql = "DELETE FROM furniture_types WHERE image_id = :img_id";
    $result_types = exec_sql_query($db, $sql, $params);

    //Delete from price range table
    $sql = "DELETE FROM furniture_price_range WHERE image_id = :img_id";
    $result_price = exec_sql_query($db, $sql, $params);

    if ($result_images && $result_types && $result_price) {
      // Delete actual file from server
      if(unlink($file_path)) {
        array_push($notify, "Successfully deleted image!");
      }
      else {
        array_push($notify, "Failed to delete image.");
      }
    }
    else {
      array_push($notify, "Failed to delete image.");
    }
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css"/>

  <title>Furniture</title>
</head>

<body>
<header>
<?php include("includes/navigation.php");?>
</header>

<h2 class = "headers"> Furniture for Sale </h2>

<section class="content1">

    <?php
    $sql = "SELECT * FROM types";
    $all_types = exec_sql_query($db, $sql)->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="_album">
    <?php
    foreach($all_types as $type){
      $type_id = $type['id'];
      $sql = "SELECT * FROM images INNER JOIN furniture_types ON furniture_types.image_id = images.id INNER JOIN types ON types.id = furniture_types.type_id
      WHERE types.id=$type_id";
      $images_with_type = exec_sql_query($db, $sql)->fetchAll(PDO::FETCH_ASSOC);
      // var_dump(($images_with_type));
      if ($images_with_type){
        // use this to create album cover photo
        $thumbnail = $images_with_type[0];



        echo "<figure>
                  <a href='album.php?id=". $type_id . "'>
                    <img class='album_cover' src='/uploads/images/" . $thumbnail['image_id'] . "." . $thumbnail['file_ext'] . "' alt='" . $thumbnail['file_name'] . "'>
                  </a>
                  <figcaption class='album_caption'>". $thumbnail['type']." </figcaption>
                  </figure>";
                  // Images taken by Nigel (client)
      }
    }

   ?>
   </div>

    <!-- Add images here when we get them -->
    <!-- displaying photo gallery from here. -->

    <?php
?>

<?php
    //form for the adding of images
if (!is_null($current_user)) { ?>

  <?php echonotify($notify) ?>
  <div id="upload-label">
    Upload New Image
  </div>
  <div id="upload">
    <fieldset class="upload_file">
    <form id="upload-form" action="furniture.php" method="post" enctype="multipart/form-data">

    <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />

    <label>Upload File:</label> <input type="file" name="image_file" id="upload-file" required>

   <label> Name: </label><input type="text" name="image_name" placeholder="Name" required>

    <label>Price:</label> <input type="number" name="add_price" placeholder="Price" required>

    <label>Price Range:</label> <select name="pricerange">
      <option value="" selected disabled>Select a Price Range</option>
                     <?php
                       // Populate dropdown with already existing tags
                       $records = exec_sql_query($db, "SELECT * FROM price_range", array())->fetchAll(PDO::FETCH_ASSOC);
                       foreach($records as $record) {
                           echo "<option value='" . $record['id'] . "'>" . $record['price_range'] . "</option>";
                       }
                     ?>
      </select>

    <label>Type of Furniture:</label> <select name="add_type" required>
                       <option value="" selected disabled>Select the type</option>
                        <?php
                          $sql = "SELECT * FROM types";
                          $records = exec_sql_query($db, $sql)->fetchAll(PDO::FETCH_ASSOC);
                          foreach($records as $record) {
                            echo "<option value='" . $record["id"] . "'>" . $record["type"] . "</option>";
                          }
                        ?>
                       </select>

    <label>Description: </label><textarea name="description" cols="100" rows="3" placeholder="Write a short description of the image." required></textarea>
    <button name="submit_upload" type="submit" id="upload-button">Upload</button>
</form>
</fieldset>
</div>

<?php
}
?>

<div class="push"></div>

</section>
<?php include("includes/footer.php");?>


</body>
</html>
