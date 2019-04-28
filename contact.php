<!-- Copyright 2019 Energize Apps.  All rights reserved. -->

<?php
  require_once $_SERVER["DOCUMENT_ROOT"]."/../common/util.php";

  function contact( $titleClass, $titleWho, $iWe, $signature, $fgColor="", $hoverColor="", $bgImage="" )
  {
    error_log( "====> post=" . print_r( $_POST, true ) );

    initStyle( $fgColor, $hoverColor, $bgImage );

    if ( count( $_POST ) == 0 )
    {
      showContactForm( $titleClass, $titleWho );
    }
    else
    {
      sendContactMessage( $iWe, $signature );
    }
  }

  function sendContactMessage( $iWe, $signature )
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

    global $mailto;
    if ( mail( $mailto, $subject, $text, $headers ) )
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
  function initStyle( $fgColor, $hoverColor, $bgImage )
  {
?>
  <style>

    <?php
      if ( $fgColor != "" )
      {
    ?>
        label, p.h3, p.h2, .form-control
        {
          color: <?=$fgColor?>;
        }
        form .fa-envelope
        {
          color: <?=$fgColor?>;
          background-color: transparent;
        }
        form .fa-envelope:hover
        {
          color: <?=$hoverColor?>;
          background-color: <?=$fgColor?>;
          padding: 0px 3px;
        }
    <?php
      }
    ?>

    <?php
      if ( $bgImage != "" )
      {
    ?>
        body
        {
          background-image: url( "<?=$bgImage?>" );
          background-position: center top;
          background-repeat: no-repeat;
          background-attachment: fixed;
          background-size: cover;
        }

        .form-control
        {
          background-color: transparent;
        }
    <?php
      }
    ?>
  </style>
<?php
  }
?>

<?php
  function showContactForm( $titleClass, $titleWho )
  {
    global $mailto;
?>
    <form id="contactForm" role="form" onsubmit="return onSubmitContact();" method="post" enctype="multipart/form-data" >

      <p class="<?=$titleClass?>">
        <span style="padding-right: 1em;">
          <b>Contact <?=$titleWho?></b>
        </span>
        <a href="mailto:<?=$mailto?>">
          <i class="fas fa-envelope" title="<?=$mailto?>">
          </i>
        </a>
      </p>

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
