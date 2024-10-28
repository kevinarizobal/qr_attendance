<?php
include('connect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['std_no'])) {
    // If not, redirect to login page
    header("Location: index.php");
    exit();
}

$std_no =  $_SESSION['std_no'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QR Code Generator</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 50px;
    }
    h1 {
      color: #333;
    }
    .container {
      margin-top: 20px;
    }
    #qrcode {
      margin-top: 20px;
    }
    input[type="text"], button {
      padding: 10px;
      margin: 10px;
    }
    button {
      background-color: #28a745;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #218838;
    }
    #download-btn {
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h1>Test Student QR Code Generator</h1>

  <div class="container">
    <input type="text" id="text" value="<?php echo $std_no;?>" placeholder="Enter text for QR Code" readonly/>
    <button onclick="generateQRCode()">Generate QR Code</button>
    <div id="qrcode"></div>
    <a id="download-btn" href="#" download="qrcode.png">Download QR Code</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
  <script>
    function generateQRCode() {
      const text = document.getElementById('text').value;
      if (text.trim() === '') {
        alert('Please enter text to generate QR Code');
        return;
      }

      // Clear any previous QR code
      document.getElementById('qrcode').innerHTML = '';

      // Generate the QR code
      const qrCodeCanvas = document.createElement('canvas');
      QRCode.toCanvas(qrCodeCanvas, text, { width: 200 }, function (error) {
        if (error) console.error(error);
      });

      // Append QR code to the div
      document.getElementById('qrcode').appendChild(qrCodeCanvas);

      // Set up download link
      qrCodeCanvas.toBlob(function (blob) {
        const url = URL.createObjectURL(blob);
        const downloadBtn = document.getElementById('download-btn');
        downloadBtn.href = url;
        downloadBtn.style.display = 'block';
      });
    }
  </script>

</body>
</html>
