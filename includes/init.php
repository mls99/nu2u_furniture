<?php
// vvv DO NOT MODIFY/REMOVE vvv
function print_contact($record) {
  ?>
  <tr>
    <td><?php echo htmlspecialchars($record["viewer_name"]);?></td>
    <td><?php echo htmlspecialchars($record["viewer_email"]);?></td>
    <td><?php echo htmlspecialchars($record["viewer_question"]);?></td>
  </tr>
  <?php
}
// check current php version to ensure it meets 2300's requirements
function check_php_version()
{
  if (version_compare(phpversion(), '7.0', '<')) {
    define(VERSION_MESSAGE, "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
    echo VERSION_MESSAGE;
    throw VERSION_MESSAGE;
  }
}
check_php_version();

function config_php_errors()
{
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}
config_php_errors();

// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename)
{
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (file_exists($init_sql_filename)) {
      $db_init_sql = file_get_contents($init_sql_filename);
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    } else {
      unlink($db_filename);
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return null;
}

function exec_sql_query($db, $sql, $params = array())
{
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return null;
}

// ^^^ DO NOT MODIFY/REMOVE ^^^
$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');
// You may place any of your code here.

// -- LOGIN & LOGOUT ---
// This code belongs to Kyle Harms (Professor of 2300) and is similar to the code he gave us to be able to have login and logout functions for this assignment

// this is the code to make the length of the cookie in order to be a duration of 1 hour before it logs the person out

define('SESSION_COOKIE_DURATION', 60*60*1);

$session_messages = array();

//this is the start of the log in function for Nigel's website

function log_in($user_name, $password) {
  global $db;
  global $current_user;
  global $session_messages;

  if ( isset($user_name) && isset($password) ) {
  //this checks if the user_name is in the database/ if it exists

  //creates an array of the user_names from the database
  $sql = "SELECT * FROM users WHERE user_name = :user_name;";
  $params = array (
    ':user_name' => $user_name
  );
  $records = exec_sql_query($db, $sql, $params) -> fetchAll();

  if ($records) {
    //Since the user_name is unique, we only need to find 1 record from the array.
    $account = $records[0];

    //Now need to check that the passiwrd is hte same as the one that is hashed in the database

    if ( password_verify($password, $account ['password']) ) {
      //Make a session for this hour long login time

      $session = session_create_id();

      //Store the session ID in the database

      $sql = "INSERT INTO sessions (user_id, session) VALUES (:user_id, :session);";
      $params = array (
        ':user_id' => $account['id'],
        ':session' => $session
      );

      $result = exec_sql_query($db, $sql, $params);

      if ($result){
        //This means that result is true, and then you want the session to be stored in the database

        //This will also send the cookie back to the user

        setcookie("session", $session, time() + SESSION_COOKIE_DURATION);

        $current_user = $account;
        return $current_user;
      } else {
        array_push($session_messages, "Log in has failed. Please try to input a new username and password!");
      }
    } else {
      array_push($session_messages, "You have an invalid username and password. Please try again!");
    }
  }  else {
    array_push($session_messages, "You have an invalid username and password. Please try again!");
    }
  } else {
    array_push($session_messages, "You have not   given a username or password.");
    }
  $current_user = NULL;
  return NULL;
  }
//this function is to push message
  $notify = array();
function echonotify($notify){
  global $notify;
  foreach ($notify as $notification) {
    echo "<p><strong>" . htmlspecialchars($notification) . "</strong></p>\n";
  }
}

  //this function is able to check if the username that is inputted is the same as what is in the database
  function find_user($user_id) {
    global $db;

    $sql = "SELECT * FROM users WHERE id = :user_id;";
    $params = array (
      ':user_id' => $user_id
    );
    $records = exec_sql_query($db, $sql, $params) -> fetchAll();
    //since users are unique you need to grab the single record from the databse
    if ($records) {
      return $records[0];
    }
    return NULL;
  }

  //this function is able to find the session for this particular username and passowrd during the alotted time
  function find_session($session) {
    global $db;

    if (isset($session)) {
      $sql = "SELECT * FROM sessions WHERE session = :session;";
      $params = array (
        ':session' => $session
      );
      $records = exec_sql_query($db, $sql, $params) -> fetchAll();
      if ($records) {
        //since the sessions are unique, there will only be one record to match
        return $records[0];
      }
    }
    return NULL;
  }

  //this functions finds the session and then also renews the cookie one more hour if login again
  function session_login() {
    global $db;
    global $current_user;

    if (isset($_COOKIE["session"])) {
      $session  = $_COOKIE["session"];

      $session_record = find_session($session);

      if ( isset($session_record) ) {
        $current_user = find_user($session_record['user_id']);

      //This is in order for the cookie to be able to renew for another hour

      setcookie("session", $session, time() + SESSION_COOKIE_DURATION);

      return $current_user;
    }
  }


  $current_user = NULL;
  return NULL;
}



function is_user_logged_in() {
  global $current_user;
  //if the current_uer is not NULL, this means that a user is logged in

  return($current_user != NULL);
}

//function in order to have the cookie be removed and this means that the session is over and the user is logged out
function log_out() {
  global $current_user;

  //remove the session from the cookie and also have time go back (expire)

  setcookie('session', '', time() -
  SESSION_COOKIE_DURATION);
  $current_user = NULL;
}

//Both login and logout requests and also to see if you need to keep the user logged in

//Check if the user should be logged in

if ( isset($_POST['login']) && isset($_POST['user_name']) && isset($_POST['password']) ){
  $user_name = trim( $_POST['user_name'] );
  $password = trim( $_POST['password'] );

  log_in($user_name, $password);
} else {
  //this is to check if logged in already via cookie

  session_login();
}

//check if the user should be logged out

if ( isset($current_user) && ( isset($_GET['logout']) || isset($_POST['logout']) ) ) {
  log_out();
}

//types of image to insert
$image_formats = array("jpg", "jpeg", "jpe", "jif", "jfif", "jfi", "gif", "png", "apng", "svg", "bmp", "ico");

?>
ß
