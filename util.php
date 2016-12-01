<?php
  // Copyright 2016 Energize Apps.  All rights reserved.

  session_start();

  function initUi( $navbarCsvLocation = "" )
  {
    global $siteName;
    global $footer;
    global $mailto;
    global $navbarItems;
    global $navbarKeys;
    global $navbarItemKey;
    global $navbarItemIndex;
    global $title;

    define( "NAVBAR_TEST", "NAVBAR_TEST" );

    $navbarHideItems = [];
    if ( $navbarHideEnv = getenv( "NAVBAR_HIDE" ) )
    {
      $navbarHideItems = explode( ",", $navbarHideEnv );
    }

    $file = fopen( $navbarCsvLocation . "navbar.csv", "r" );
    $siteName = fgetcsv( $file )[0];
    $footer = fgetcsv( $file )[0];
    $mailto = fgetcsv( $file )[0];

    $navbarItems = array();
    $navbarKeys = array();
    while( ! feof( $file ) )
    {
      $item = fgetcsv( $file );
      if ( ! empty( trim( $item[0] ) ) && ! ( strpos( $item[0], "#" ) === 0 ) )
      {
        $key = trim( $item[0] );
        if ( ! in_array( $key, $navbarHideItems ) )
        {
          array_push( $navbarKeys, $key );
          $navbarItems[$key] = [ trim( $item[1] ), trim( $item[2] ) ];
          if ( count( $item ) > 3 )
          {
            array_push( $navbarItems[$key], trim( $item[3] ) );
          }
        }
      }
    }

    fclose( $file );

    // Get the current navigation bar selection
    $navbarItemKey = ( isset( $_GET["page"] ) ? $_GET["page"] : $navbarKeys[0] );
    $navbarItemIndex = array_search( $navbarItemKey, $navbarKeys );
    $title = $navbarItems[$navbarItemKey][0] . " - " . $siteName;
  }

  function downloadFile( $filename, $ext = "", $type = "octet-stream" )
  {
    header( 'Content-Description: File Transfer' );
    header( 'Content-Type: application/' . $type );
    header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . $ext . '"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . filesize( $filename ) );
    readfile( $filename );
  }

  function markFile( $filename )
  {
    $file = fopen( $filename, "a" );
    fwrite( $file, md5( file_get_contents( $filename ) ) );
    fclose( $file );
  }

  function unmarkFile( $filename )
  {
    // Determine size of file containing MD5 hash
    $filesize = filesize( $filename );

    //
    // Retrieve MD5 hash and remove from file
    //

    // Open file
    $file = fopen($filename, 'r+');

    // Consume trailing control characters
    $cursor = 0;
    do
    {
      fseek( $file, $cursor, SEEK_END );
      $char = fgetc( $file );
      if ( ! ctype_print( $char ) )
      {
        $cursor--;
      }
    }
    while ( ! ctype_print( $char ) );

    // Recover saved MD5 hash
    $md5Hash = "";
    do
    {
      fseek( $file, $cursor, SEEK_END );
      $char = fgetc( $file );
      if ( ctype_print( $char ) )
      {
        $md5Hash = $char.$md5Hash;
        $cursor--;
      }
    }
    while ( ctype_print( $char ) );

    // Truncate file
    ftruncate( $file, $filesize + $cursor + 1 );

    // Close file
    fclose( $file );

    // Validate MD5 hash of unmarked file with MD5 hash retrieved from file
    $messages = [];
    if ( $md5Hash !== md5( file_get_contents( $filename ) ) )
    {
      array_push( $messages, "Checksum validation failed" );
    }

    return $messages;
  }
?>
