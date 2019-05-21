<?php
 // INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$title= 'Reviews';
$messages = array();
function print_record($record){
    ?>
    <tr>
        <td><?php echo htmlspecialchars($record["reviewer"]);?></td>
        <td><?php echo htmlspecialchars($record["comment"]);?></td>
</tr>
<?php
}
//insert form
$reviews= exec_sql_query($db, "SELECT DISTINCT * FROM reviews", NULL)->fetchAll();
if ( isset($_POST["leave_review"]) ){
    $valid_review = TRUE;

    $reviewer = filter_input(INPUT_POST, 'reviewer', FILTER_SANITIZE_STRING);
    $comment = filter_input(INPUT_POST,'comment', FILTER_SANITIZE_STRING);


    if($valid_review){
        $sql = "INSERT INTO reviews (reviewer, comment) VALUES (:reviewer, :comment)";
        $params  = array(
            ':reviewer'=>$reviewer,
            ':comment'=>$comment,
        );
        $result = exec_sql_query($db, $sql, $params);
        if($result){
            array_push($messages, "Thank you for submitting your review!");
        } else {
            array_push($messages, "Your review was not added, please try agian");
        }
    }else{
        array_push($messages, "Your review did not add, please check that you have filled in your name and comment");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="styles/all.css"/>
  <title>Reviews</title>
</head>

<body>
<?php include("includes/navigation.php");?>

<section class="content1">
<h2 class = "headers">Reviews from Previous Customers</h2>
<?php
$sql = "SELECT * FROM reviews";
$params = array();
$result = exec_sql_query($db, $sql, $params);
if ($result) {
    $records = $result ->fetchAll();
    if (count($records)>0){
        ?>
        <table id="review_table">
            <tr>
                <th>Reviewer</th>
                <th>Review</th>
            </tr>
        <?php
        foreach($records as $record) {
            print_record($record);
        }
        ?>
        </table>
    <?php
    } else{
        echo "<p> No matching reviews found.</p>";
    }
}
?>

<h2>Add your own review of NU2U Furniture!</h2>

<form action="reviews.php" id="leave_review_" method="post">
    <label>Your name</label>
    <input type="text" name="reviewer"/>
    <label>Review</label>
    <textarea name="comment" form="leave_review_" rows="20" cols="60">Leave Your Review!</textarea>
    <button type="submit" name="leave_review">Submit</button>
</form>

<div class="push"></div>

</section>

<?php include("includes/footer.php");?>

</body>
</html>
