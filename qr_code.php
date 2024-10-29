<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 10px 10px;
        }
        .content {
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        #qrcode-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }
        #qrcode canvas {
            margin-bottom: 10px;
        }
        #download-btn {
            display: none;
        }
    </style>
</head>
<body>
<?php include ("navbar.php");?>

<div class="container mt-5">
    <div class="content">
      <div class="row">
        <div class="col-10">
          <input type="text" id="text" value="<?php echo $std_no;?>" placeholder="Enter text for QR Code" class="form-control" readonly/>
        </div>
        <div class="col-2">
          <button class="btn btn-primary" onclick="generateQRCode()">Generate QR Code</button>
        </div>
      </div>

      <div class="container mt-4">
        <div id="qrcode-container">
          <div id="qrcode"></div>
          <a class="btn btn-success" id="download-btn" href="#" download="qrcode.png">Download QR Code</a>
        </div>
      </div>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
</html>
