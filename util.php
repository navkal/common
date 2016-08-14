<?php
  ini_set( "error_log", $_SERVER["DOCUMENT_ROOT"]."/php_errors.log" );

  function initUi( $navbarCsvLocation = "" )
  {
    global $title;
    global $footer;
    global $navbarItems;
    global $navbarItemIndex;

    $file = fopen( $navbarCsvLocation . "navbar.csv", "r" );
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

  function downloadFile( $filename )
  {
    header( 'Content-Description: File Transfer' );
    header( 'Content-Type: application/octet-stream' );
    header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . filesize( $filename ) );
    readfile( $filename );
  }
?>
