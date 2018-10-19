<!-- Copyright 2018 Energize Apps.  All rights reserved. -->

<!DOCTYPE html>
<html lang="en">
  <?php
    include "head.php";
  ?>
	<body>
    <?php
      // Navigation bar and labels
      initUi();
      include 'navbar' . BOOTSTRAP_VERSION . '.php';
    ?>

    <div id="view" style="padding-top:70px;padding-bottom:80px">
      <?php
        // Content corresponding to navigation bar selection
        include isset( $navbarItems[$navbarItemKey][1] ) ? $navbarItems[$navbarItemKey][1] : 'error.php';
      ?>
    </div>

    <?php
      // Sticky footer
      include 'footer' . BOOTSTRAP_VERSION . '.php';
    ?>
 	</body>
</html>
