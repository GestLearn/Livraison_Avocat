<!DOCTYPE html>
<html>
<head>
    <title>PHP Signature Pad Example</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.css">
  
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
  
    <style>
        .kbw-signature { width: 400px; height: 200px;}
        #signatureCanvas {
            width: 100%;
            max-width: 400px;
            height: auto;
            border: 1px solid #000;
        }
    </style>
  
</head>
<body>
  
<div class="container">
  
    <form method="POST" action="upload.php">
  
        <h1>PHP Signature Pad Example</h1>
  
        <div class="col-md-12">
            <label class="" for="">Signature:</label>
            <br/>
            <canvas id="signatureCanvas" width="400" height="200"></canvas>
            <br/>
            <button id="clear">Clear Signature</button>
            <!-- <input type="hidden" id="signature64" name="signed"> -->
            <!-- <textarea id="signature64" name="signed" style="display: none"></textarea> -->
            <input type="hidden" id="signatureImage" name="signatureImage">

        </div>
        <input type="hidden" name="record_id" value="<?php echo $_GET['id']; ?>">

        <br/>
        <button class="btn btn-success">Submit</button>
    </form>
  
</div>
  
<script type="text/javascript">
    var canvas = document.getElementById('signatureCanvas');
    var signaturePad = new SignaturePad(canvas);

    $('#clear').click(function(e) {
    e.preventDefault();
    if (!signaturePad.isEmpty()) {
        var signatureDataUrl = signaturePad.toDataURL(); // Get the signature as a data URL
        $("#signatureImage").val(signatureDataUrl); // Set the data URL as the value of the input field
    }
    signaturePad.clear();
});

</script>
  
</body>
</html>
