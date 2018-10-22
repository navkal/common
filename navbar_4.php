<!-- Copyright 2018 Energize Apps.  All rights reserved. -->

<nav class="navbar fixed-top navbar-light bg-light" style="border-bottom:1px solid Gainsboro;">

  <!-- Brand -->
  <a class="navbar-brand" href="?page=<?=$navbarKeys[0]?>" onclick="navigate(event,0)" >
    <img alt="<?=$siteName?>" src="brand.ico" style="height:25px">
  </a>

  <!-- Hamburger -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Menu -->
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php
        $idx = 0;
        foreach ( $navbarItems as $item )
        {
          if ( $item[count($item)-1] != "NAVBAR_TEST" )
          {
            echo '<li class="nav-item"><a class="nav-link" href="?page='.$navbarKeys[$idx++].'">'.$item[0].'</a></li>';
          }
        }
      ?>
    </ul>
  </div>

</nav>

<script>
  // Initialize favicon, title, and navbar
  $( document ).ready(
    function()
    {
      // Initialize favicon
      $( 'head' ).append( '<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />' );

      // Set the title
      document.title = "<?=$title?>";

      // Set expansion behavior
      $( 'nav.navbar.fixed-top' ).addClass( '<?=defined( "NAVBAR_EXPAND_CLASS" ) ? NAVBAR_EXPAND_CLASS : "navbar-expand-md"?>' );

      // Highlight selected item in navigation bar
      var idx = parseInt( "<?=$navbarItemIndex?>" );
      $( $( ".navbar div ul li" )[idx] ).addClass( "active" );

      // Set click handler for navigation bar items
      $( ".navbar div ul li a" ).click( navigate );
    }
  );

  // Handle click on navigation bar item
  function navigate( event, idx )
  {
    $( "body" ).css( "cursor", "progress" );
  }
</script>
