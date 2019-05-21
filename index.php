<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$title= 'Home';
function echomessage($messages){
  foreach ($messages as $message) {
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
  }
}
$messages = array();
if ( isset($_POST["submit_insert"]) ) {
  $valid_review = TRUE;

  $viewername = filter_input(INPUT_POST, 'viewername', FILTER_SANITIZE_STRING);
  $Email = filter_input(INPUT_POST, 'vieweremail', FILTER_VALIDATE_EMAIL);
  $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);

  // check every field to be not null and check rating
  if ($viewername=='' or $Email=='' or $question==''){
    $valid_review = FALSE;
  }
  if ($valid_review) {
    $sql = "INSERT INTO contact (viewer_name,viewer_email,viewer_question) VALUES(:viewer_name, :viewer_email, :viewer_question)";
    $params = array(
      ':viewer_name' => $viewername,
      ':viewer_email' => $Email,
      ':viewer_question' => $question
    );
    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($messages, "Your contact has been recorded. Thank you!");
    }
}
else {
  array_push($messages, "Failed to add contact. Invalid input.");
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css"/>
  <title>Home</title>
</head>

<body>
<?php include("includes/navigation.php");?>


<h2 id="subtitle">Unecessary Header - Used for Parallax Purposes</h2>
<div class="parallax" id="section1"></div>
  <div class="container">
    <div class="content">
  <h2 class="parallax_subheader">About Nu2u Furniture</h2>
    <p>Nu2u Furniture provides contemporary and vintage furniture with a smatting of antiques. Nu2u Furniture specializes in Mid Century, Mid Century Modern, 1960s and 1970s Modern, and Vintage Metal/Industrial office items, especially Steelcase desks. The business has a private warehouse used for sales, by appointment only. Nu2u Furniture is committed to its sustainability practices and is especially proud of its fair labor standards.</p>
    <p>The services provided include delivery and transport services including long distance, furniture painting, refinishing, repair, modification and restoration services, and free furniture removal.</p>
    <p>To set up an appointment to check out any of the furniture in our warehouse space near downtown Ithaca, please contact anytime by email or phone. Location, directions, and maps will be provided upon making the appointment.</p>
    </div>
  </div>

  <div class="parallax" id="section2"></div>
  <div class="container">
    <div class="content">
      <h2 class="parallax_subheader">NU2U Furniture rules of sale</h2>
        <p>All sales are cash only. Payment by USPS Money Order are a possibility upon request.</p>
        <p><strong>Nu2u does not buy or trade furniture</strong></p>
    </div>
  </div>

  <div class="parallax" id="section3"></div>
  <div class="container">
    <div class="content">
      <h2 class="parallax_subheader">NU2U Furniture Sustainability and Fair Practices</h2>
       <p>NU2U is very proud of its contributions to the Ithaca community. We provide quality, much needed merchandise to the community at fair and below market prices that meed the budget of working class customers. We cater specifically to the diversity of local residents specifically college students who need quality items quickly, easily, and cheaply.  NU2U establishes and builds better local ties with the Ithaca community through friendly personal business.</p>
	    <p>We are proud of the reuse aspect of the company, using socially progressive methods of reusing furniture that is seemingly useless to others. Reusing these items prevents them from becoming waste in a landfill, and they are instead refurbished to new items to be used again, preventing the purchase of new items. </p>
	    <p>This business is dedicated to the idea of paying a good wage for the good work and fighting labor exploitation through setting a positive example to the community. </p>
      </div>
</div>


<?php
if (is_user_logged_in()){
  $sql = "SELECT * FROM contact";
  $params = array();
  $result = exec_sql_query($db, $sql, $params);
  if ($result) {
    $records = $result->fetchAll();
    if ( count($records) > 0 ) {
      ?>
    <h1>Contact Information of Viewers</h1>
      <table id="contact_table">
    <tr>
      <th>Viewer Name</th>
      <th>Viewer Email</th>
      <th>Questions</th>
    </tr>
    <?php
    foreach($records as $record) {
      print_contact($record);}
    }}
    ?>
  </table>
  <?php
} else{
?>
<fieldset>
  <?php echomessage($messages)?>
<h2 id="contactinfo_header">Get in Touch!</h2>
<form id="contactinfo" action="index.php" method="post">
  <ul>
    <li>
      <label>Name:</label>
      <input type="text" name="viewername"/>
    </li>
    <li>
      <label>Email:</label>
      <input type="text" name="vieweremail"/>
    </li>
    <li>
      <label>Questions & Comments:</label>
    </li>
    <li>
      <textarea name="question" cols="40" rows="5"></textarea>
    </li>
    <li>
      <button name="submit_insert" type="submit">Submit</button>
    </li>
    </ul>
  </form>
</fieldset>
<?php
}

  ?>
<?php include("includes/footer.php");?>

</body>
</html>
