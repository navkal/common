<?php
  session_start();

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
      if ( ! empty( trim( $item[0] ) ) && ! ( strpos( $item[0], "#" ) === 0 ) )
      {
        array_push( $navbarItems, $item  );
      }
    }

    fclose( $file );

    // Get the current navigation bar selection
    $navbarItemIndex = ( isset( $_GET["nav"] ) ? $_GET["nav"] : 0 );
  }

  function downloadFile( $filename, $type = "octet-stream" )
  {
    header( 'Content-Description: File Transfer' );
    header( 'Content-Type: application/' . $type );
    header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
    header( 'Expires: 0' );
    header( 'Cache-Control: must-revalidate' );
    header( 'Pragma: public' );
    header( 'Content-Length: ' . filesize( $filename ) );
    readfile( $filename );
  }

  function markFile( $filename )
  {
    $_SESSION["markFile"] = file_get_contents( $filename );
    error_log( "====> ses markFile=" . $_SESSION["markFile"] );
    $lines = file( $filename );
    error_log( "====> markFile BF: " . print_r( $lines, true ) );
    $file = fopen( $filename, "a" );
    fwrite( $file, md5( file_get_contents( $filename ) ) );
    fclose( $file );
    $lines = file( $filename );
    error_log( "====> markFile AF: " . print_r( $lines, true ) );
  }

  function unmarkFile( $filename )
  {
    $lines = file( $filename );
    error_log( "====> unmarkFile BF: " . print_r( $lines, true ) );
    error_log( "===> md5Hash = " . $lines[ count( $lines ) - 2 ] );


    $filesize = filesize( $filename );

    // Consume trailing control characters
    $file = fopen($filename, 'r+');
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


    error_log( "======> md5Hash=" . $md5Hash );
    ftruncate( $file, $filesize + $cursor + 1);
    fclose( $file );

    $lines = file( $filename );
    error_log( "====> unmarkFile AF: " . print_r( $lines, true ) );

    $_SESSION["unmarkFile"] = file_get_contents( $filename );

    error_log( "====> ses markFile=<" . $_SESSION["markFile"] . ">" );
    error_log( "====> ses unmarkFile=<" . $_SESSION["unmarkFile"] . ">" );
    error_log( "======> Are file contents the same? " . ( ( $_SESSION["markFile"] == $_SESSION["unmarkFile"] ) ? "YES" : "NO" ) );

    $newMd5Hash = md5( file_get_contents( $filename ) );
    error_log( "====> New MD5 hash=" . $newMd5Hash );
    error_log( "======> Are hash values the same? " . ( ( $md5Hash == $newMd5Hash ) ? "YES" : "NO" ) );

    $testMessage = "$md5Hash ==?== $newMd5Hash";
    error_log( $testMessage );
    return [ $testMessage ];
  }
?>
