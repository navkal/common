<!-- Copyright 2016 Energize Apps.  All rights reserved. -->

<!DOCTYPE html>
<html lang="en">
  <?php
    include "head.php";
  ?>
	<body>
    <?php
      // Navigation bar and labels
      initUi();
      include "navbar.php";
    ?>

    <div id="view" style="padding-top:70px;padding-bottom:80px">
      <?php
        // Content corresponding to navigation bar selection
        include $navbarItems[$navbarItemIndex][1];
      ?>
    </div>

    <?php
      // Sticky footer
      include "footer.php";
    ?>
 	</body>
</html>
