<?php
    require '../controllers/config.php';

    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: /views/login.php");
        exit; // Terminate the script to prevent further execution
    }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Draw & Download Signature</title>
    <!-- costumised css -->
    <link rel="stylesheet" href="../public/style.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/public/js/jSignature-main/jSignature.min.js"></script>
    <script src="/public/js/jSignature-main/modernizr.js"></script>

    <script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.js"></script>

    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  
  </head>
  <body>
    <?php 
      // :require '../controllers/config.php';
      require './navbar.php';
    
    ?>
    <div id="signature" style="border: 1px solid black;" class="parent"></div>

    <button type="button" id="preview">Preview</button>

    <img src="" id="signaturePreview">
    <img src="" id="signaturePreview2" hidden>

    <form id="uploadForm" method="POST" action="/controllers/uploadNew.php">
      <input type="hidden" name="record_id" value="<?php echo $_GET['id']; ?>">
      <input type="text" id="filename" name="filename" required hidden>
      <input type="submit" value="Upload" hidden>
    </form>

    <!-- Remove the 'download' attribute from the anchor element -->
    <a href="#" id="download">Download</a>

    <script type="text/javascript">
      var signature = $("#signature").jSignature({ 'UndoButton': true });

      $('#preview').click(function () {
        var data = signature.jSignature('getData', 'image');
        $('#signaturePreview').attr('src', "data:" + data);
      });


      
      $('#download').click(function () {
        // Get the jSignature data
        var $signature = $("#signature");
        var signatureData = $signature.jSignature('getData', 'image');

        // Check if there is any data
        if (!signatureData || !signatureData[1]) {
          alert("Please draw a signature before downloading.");
          return;
        }

        // Convert the signature data to a data URL (image)
        var imageBase64 = signatureData[1];

        // Generate a unique filename using a timestamp
        var timestamp = new Date().getTime();
        var filename = "signature_" + timestamp + ".png";

        // Set the filename in the form input
        $('#filename').val(filename);
        var recordId = <?php echo json_encode($_GET['id']); ?>;
        // Submit the form to your backend using AJAX
        $.ajax({
          type: "POST",
          url: "/controllers/uploadNew.php", // Specify your server-side script URL
          data: {
            record_id: recordId,
            filename: filename,
            imageBase64: imageBase64 // Send the image data as base64
          },
          success: function (response) {
            // Handle the response from the server if needed
            console.log(response);
            window.location.href = "/home.php";
          }
        });

        
      });


    
    </script>
  </body>
</html>
