<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$title= 'Login';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css"/>

  <title>Log In</title>
</head>

<body>
<?php include("includes/navigation.php");?>

<section class="content-login">

<h2 class="headers-login">Admin Log In Form</h2>

<form id="loginForm" action="index.php" method="post">


  <div class="input">
    <label class="label-login" for="user_name">Username: </label>
    <input id="user_name" type="text"
    name="user_name">
  </div>


  <div class="input">
    <label class="label-login"  for="password">Password: </label>
    <input id="password" type="password" name="password">
  </div>


  <div class="signin">
    <button type="submit" name="login">Sign In</button>
  </div>

  <?php

  //This is for the messages for login - to say if the password or username is correct

    foreach ($session_messages as $message) {
      echo "<strong>" . htmlspecialchars($message) . "<strong>\n";
    }

    //Log Out Link

    if ( is_user_logged_in() ) {
      //This is the logout query string for the user

      $logout_url = htmlspecialchars( $_SERVER['PHP_SELF'] ) . '?' . http_build_query( array( 'logout' => '') );
      echo '<a class="login" href="' . $logout_url . '">Sisffsadfsasdfgn Out ' . htmlspecialchars($current_user['user_name']) . '</a>';
    }

    ?>

  </form>

<div class="push"></div>

</section>

<?php include("includes/footer.php");?>


</body>
</html>
