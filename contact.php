<!-- Copyright 2016 Energize Apps.  All rights reserved. -->

<style>
  body
  {
    background-image: url( "contact/bg.jpg" );
    background-position: center top;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }

  label, p.h3, p.h2, .form-control
  {
    color: white;
  }

  .form-control
  {
    background-color: transparent;
  }
</style>

<?php

  function sendContactMessage( $to, $iWe, $signature )
  {
    $name = $_POST["firstName"] . " " . $_POST["lastName"];
    $subject = "From " . $name;
    $comment = str_replace( "\n", "<br/>", $_POST["comment"] );

    $text =
      "<style>body{font-family: arial;}</style>" .
      "<html><body>".
      "<h4><u>Name</u></h4><span>" . $name . "</span>" .
      "<hr/>" .
      "<h4><u>Email</u></h4><p>" . $_POST["email"] . "</p>" .
      "<hr/>" .
      "<h4><u>Subject</u></h4><p>" . $_POST["subject"] . "</p>" .
      "<hr/>" .
      "<h4><u>Comment</u></h4><p>" . $comment . "</p>" .
      "<hr/>" .
      "</html></body>";


    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . $_POST["email"] . "<SmtpDispatch@gmail.com>" . "\r\n";

    if ( mail( $to, $subject, $text, $headers ) )
    {
      reportContactSuccess( $iWe, $signature );
    }
    else
    {
      reportContactError();
    }
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

<script>
  function onSubmitContact()
  {
    $( ".form-control" ).prop( "readonly", true );
    $( "#submitButton" ).prop( "disabled", true );
    $( "#cancelButton" ).prop( "disabled", true );
    return true;
  }
</script>