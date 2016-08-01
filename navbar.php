<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="?nav=0" onclick="navigate(event,0)" >
        <img alt="<?php echo $title; ?>" src="brand.ico" style="height:25px">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse">
      <ul class="nav navbar-nav">
        <?php
          $idx = 0;
          foreach ( $navbarItems as $item )
          {
            echo '<li><a href="?nav='.$idx++.'">'.$item[0].'</a></li>';
          }
        ?>
      </ul>
    </div>

  </div>
</nav>

<script>

  // Initialize favicon
  $( window ).load(
    function()
    {
      $( 'head' ).append( '<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />' );
    }
  );

  // Initialize title and navbar
  $( document ).ready(
    function()
    {
      // Set the title
      document.title = "<?php echo $title; ?>";

      // Highlight selected item in navigation bar
      var idx = parseInt( "<?php echo $navbarItemIndex; ?>" );
      $( $( ".navbar div ul li" )[idx] ).addClass( "active" );

      // Set click handler for navigation bar items
      $( ".navbar div ul li a" ).click( navigate );
    }
  );

  // Handle click on navigation bar item
  function navigate( event, idx )
  {
    $( "#view" ).css( "color", "gray" );
    $( "body" ).css( "cursor", "progress" );
    a = ( idx == null ) ? this : $( ".navbar div ul li a" )[idx];

    $( ".navbar div ul li.active" ).removeClass( "active" );
    $( a ).parent().addClass( "active" );
  }

</script>
