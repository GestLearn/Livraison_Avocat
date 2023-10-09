<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $signatureDataUrl = $_POST["signatureImage"];
    $recordId = $_POST["record_id"];

    // Generate a unique filename for the signature image
    $filename = "signature_" . $recordId . ".png"; // You can use a different extension if desired (e.g., ".jpg")

    // Remove the "data:image/png;base64," prefix from the data URL
    $signatureData = base64_decode(preg_replace('/^data:image\/(png|jpeg);base64,/', '', $signatureDataUrl));
    // echo '$signatureData'.$signatureData;
    // Specify the directory where you want to save the signature images
    $savePath = "uploads/";

    // Save the signature image to the specified directory
    if (!file_put_contents($savePath . $filename, $signatureData)) {
        error_log("Error saving signature image: " . error_get_last());
        echo "Error saving signature image.";
    } else {
        // Signature image saved successfully
        echo "Signature image saved as: " . $filename;
    }
}
?>
