<?php
  // Copyright 2016 Energize Apps.  All rights reserved.

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

<?php
  function showContactForm( $titleClass, $titleWho )
  {
?>
    <form id="contactForm" role="form" onsubmit="return onSubmitContact();" method="post" enctype="multipart/form-data" >
      <p class="<?=$titleClass?>"><b>Contact <?=$titleWho?></b></p>

      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" maxlength="32" required >
      </div>

      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" maxlength="32" required >
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" maxlength="256" required >
      </div>

      <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" class="form-control" id="subject" name="subject" maxlength="256" required >
      </div>

      <div class="form-group">
        <label for="comment">Comment</label>
        <textarea class="form-control" id="comment" name="comment" rows="5" maxlength="4096" required ></textarea>
      </div>

      <!-- Form buttons -->
      <div style="text-align:center;" >
        <button id="submitButton" type="submit" form="contactForm" class="btn btn-default" >Submit</button>
        <button id="cancelButton" type="reset" onclick="$( "input, textarea").val('');" class="btn btn-default" >Clear</button>
      </div>

    </form>
<?php
  }
?>

<?php
  function reportContactSuccess( $iWe, $signature )
  {
?>
    <br/>
    <p class="h3">Thank you for your interest.</p>
    <p class="h3"><?=$iWe?> will be in touch!</p>
    <p class="h3">- <?=$signature?></p>
<?php
  }
?>
<?php
  function reportContactError()
  {
?>
    <br/>
    <p class="h3">An error occurred while transmitting your message.</p>
    <p class="h3">Please try again later.</p>
<?php
  }
?>
