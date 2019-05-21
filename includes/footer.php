<footer class="footer">

      <figure class="logo-4">
        <!-- Image Source: Made By Lindsey  -->
        <img class="logo-footer" src="images/logo4.svg" alt="Logo" />
      </figure>

    <?php
      if (!is_user_logged_in())
      echo '<a class="login" href="login.php">Admin Log In</a>';
    ?>

  <?php
    if ( is_user_logged_in() ) {
    //This is the logout query string for the user

    $logout_url = htmlspecialchars( $_SERVER['PHP_SELF'] ) . '?' . http_build_query( array( 'logout' => '') );
    echo '<a class="login" href="' . $logout_url . '">Sign Out ' . htmlspecialchars($current_user['user_name']) . '</a>';
    }
  ?>

  <p class="craigs">For more furniture and information please look on my Craigslist!</p>

  <a class="link" href="https://ithaca.craigslist.org/search/sss?userid=194424846">Craigslist</a>


  <!-- All Images for this cite were either given to us from Nigel (the client) or taken or made by one of the group members (Lindsey) -->
  <fieldset>
  <p class="craigs">Source: All Images were given to us from Nigel (the client) or taken by one of the group members on our team.</p>
  <a href="https://ithaca.craigslist.org/search/sss?userid=194424846">Source</a>
  </fieldset>>


</footer>
