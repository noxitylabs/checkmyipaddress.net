<?php 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
if(stripos($_SERVER["HTTP_USER_AGENT"] ?? "", "curl") !== false) { 
echo preg_replace("/[^A-Za-z0-9\.\:]/", "", $ip);
exit;
} else {
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Check My IP Address
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css">
    <link href="https://unpkg.com/microtip/microtip.css" rel="stylesheet">
  </head>
  <body class="light" id="pagebody">
    <div class="content">
      <h2>Your current IP address is
      </h2>
      <div id="copy" class="ipboxdark" aria-label="Click to copy 📋" data-microtip-position="top" role="tooltip">
        <?php echo $ip; ?>
      </div>
      <h4>
        <b>What is an IP address?
        </b>
        <br>
        <span class="font-override">The language that computers use to transmit data packets across networks is called Internet Protocol. On your home/office network or the internet, your computer, mobile device, or appliance is identified by its IP address.
          <br>
          <br>
          Four 8-bit octets (0 to 255) separated by a period make up IP addresses. There are about 4,294,967,296 addresses that may be used since this creates a 32-bit numeric address. Astonishingly, they will soon run out.
          <br>
          <br>
          Don't freak out though! The IPv6 protocol was already developed by the researchers as a replacement. Having the ability to support 340,282,366,920,938,463,463,374,607,431,768,211,456 addresses, which is enough to give every atom on Earth an IP address. And have enough for around 100 more Earths.
        </span>
      </h4>
      <h5>Copyright 2022 - © Noxity.io (Gostoljub d.o.o.) - All rights reserved.
      </h5>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js">
    </script>
    <script src="modehandler.js">
    </script>
    <script>
      document.getElementById("copy").onclick = function() {
        var e = document.getElementById("copy");
        if (document.body.createTextRange)(t = document.body.createTextRange()).moveToElementText(e), t.select(), document.execCommand("Copy"), $("#copy").attr("aria-label", "Copied ✔️"), setTimeout(function() {
          $("#copy").attr("aria-label", "Click to copy 📋")
        }
, 3e3);
        else if (window.getSelection) {
          var t, o = window.getSelection();
          (t = document.createRange()).selectNodeContents(e), o.removeAllRanges(), o.addRange(t), document.execCommand("Copy"), $("#copy").attr("aria-label", "Copied ✔️"), setTimeout(function() {
            $("#copy").attr("aria-label", "Click to copy 📋")
          }
, 3e3)
        }
      };
    </script>
  </body>
</html>
<?php
}
?>
