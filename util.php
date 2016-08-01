<?php
  date_default_timezone_set( "America/New_York" );

  function initUi()
  {
    global $title;
    global $footer;
    global $navbarItems;
    global $navbarItemIndex;

    $file = fopen( "navbar.csv", "r" );
    $title = fgetcsv( $file )[0];
    $footer = fgetcsv( $file )[0];

    $navbarItems = array();
    while( ! feof( $file ) )
    {
      array_push( $navbarItems, fgetcsv( $file ) );
    }

    fclose( $file );

    // Get the current navigation bar selection
    $navbarItemIndex = ( $_GET["nav"] ? $_GET["nav"] : 0 );
  }

  function echoLine( $message )
  {
    echo $message . "<br/>";
  }

  function downloadFile( $file )
  {
    header( 'Content-Description: File Transfer' );
    header( 'Content-Type: application/octet-stream' );
    header( 'Content-Disposition: attachment; filename="' . basename( $file ) . '"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . filesize( $file ) );
    readfile( $file );
  }
?>
