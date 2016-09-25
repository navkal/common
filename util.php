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

    $lines = file( $filename );
    $file = fopen( $filename, "a" );
    fwrite( $file, md5( file_get_contents( $filename ) ) );
    fclose( $file );
    $lines = file( $filename );
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
    ftruncate( $file, $filesize + $cursor + 1);

    // Close file
    fclose( $file );


    $_SESSION["unmarkFile"] = file_get_contents( $filename );
    error_log( "====> ses markFile=<" . $_SESSION["markFile"] . ">" );
    error_log( "====> ses unmarkFile=<" . $_SESSION["unmarkFile"] . ">" );
    error_log( "======> Are file contents the same? " . ( ( $_SESSION["markFile"] == $_SESSION["unmarkFile"] ) ? "YES" : "NO" ) );

    // Determine MD5 hash of truncated file
    $md5HashNew = md5( file_get_contents( $filename ) );

    $testMessage = "$md5Hash ==?== $md5HashNew --  Are they the same? " . ( ( $md5Hash == $md5HashNew ) ? "YES" : "NO" ) ;
    error_log( $testMessage );
    return [ $testMessage ];
  }
?>
