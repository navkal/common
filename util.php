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
      $item = fgetcsv( $file );
      if ( ! ( strpos( $item[0], "#" ) === 0 ) )
      {
        array_push( $navbarItems, $item  );
      }
    }

    fclose( $file );

    // Get the current navigation bar selection
    $navbarItemIndex = ( isset( $_GET["nav"] ) ? $_GET["nav"] : 0 );
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

  function downloadZip( $zipFile, $zipFilename )
  {
    // Download the zip archive
    header( 'Content-Description: File Transfer' );
    header( 'Content-Type: application/zip' );
    header( 'Content-Disposition: attachment; filename="' . $zipFilename . '"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . filesize( $zipFile ) );
    readfile( $zipFile );
  }
?>
