<?php 
// Function to get the real IP address
function getIpAddress() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip); // Just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    return $ip; // Return IPv6 if available
                }
            }
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip); // Just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $ip; // Return IPv4 if IPv6 is not found
                }
            }
        }
    }
    return '';
}

$ip = getIpAddress();

// Check if the request is from a CURL command
if (stripos($_SERVER["HTTP_USER_AGENT"] ?? "", "curl") !== false) {
    // Check if IPv6 is specifically requested via the URL query parameter
    if(isset($_GET['mode']) && strtolower($_GET['mode']) == 'v6') {
        $ipv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        if ($ipv6 === false) {
            echo "IPv6 Not available\n";  // Display message if no IPv6 address is found
        } else {
            echo $ipv6 . "\n";  // Display the IPv6 address
        }
    } else {
        $ipv4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        if($ipv4 === false) {
            echo "IPv4 Not available\n";  // Handle case where no IPv4 is found
        } else {
            echo $ipv4 . "\n";  // Display the IPv4 address
        }
    }
    exit;
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Check My IP Address</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css">
    <link href="https://unpkg.com/microtip/microtip.css" rel="stylesheet">
    
<style>
    .command-container {
        display: flex;
        justify-content: space-between;
        gap: 20px; /* Adds space between the command boxes */
        margin-top: 10px;
    }
    .command-box {
        background-color: #d13b5b; /* Custom Red background */
        padding: 10px;
        flex: 1;
        border-radius: 5px;
        text-align: left;
        position: relative;
    }
    .command-url {
        background-color: #9f2c47; /* Darker red for the URL part */
        color: white;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        display: inline-block;
        margin-top: 5px;
        position: relative; /* Relative positioning for the tooltip */
    }
    .command-label {
        font-weight: bold;
        display: block;
        color: white;
        margin-bottom: 5px;
    }
    /* Tooltip styles */
    .command-url[aria-label]:hover:after {
        content: attr(aria-label);
        position: absolute;
        top: -35px;
        right: 0;
        background: black;
        color: white;
        padding: 5px 15px;
        border-radius: 5px;
        z-index: 20;
        white-space: nowrap;
    }
</style>
</head>
<body class="light" id="pagebody">
<div class="content">
    <h2>Your current IP address is</h2>
    <div id="copy" class="ipboxdark" aria-label="Click to copy 📋" data-microtip-position="top" role="tooltip">
        <?php echo $ip; ?>
    </div>
    <h4>
        <b>What is an IP address?</b>
        <br>
        <span class="font-override">The language that computers use to transmit data packets across networks is called Internet Protocol. On your home/office network or the internet, your computer, mobile device, or appliance is identified by its IP address.
        <br><br>
        Four 8-bit octets (0 to 255) separated by a period make up IP addresses. There are about 4,294,967,296 addresses that may be used since this creates a 32-bit numeric address. Astonishingly, they will soon run out.
        <br><br>
        Don't freak out though! The IPv6 protocol was already developed by the researchers as a replacement. Having the ability to support 340,282,366,920,938,463,463,374,607,431,768,211,456 addresses, which is enough to give every atom on Earth an IP address. And have enough for around 100 more Earths.
        </span>
    </h4>
<h4>
    <b>Using This Tool via Terminal</b>
    <br>
    <span class="font-override">
        Access your IP address directly from your terminal. Click on the appropriate command below to copy it to your clipboard.
    </span>
</h4>
<div class="command-container">
    <div class="command-box">
        <span class="command-label">Copy IPv4 Command:</span>
        <div class="command-url" onclick="copyCommand(this)">curl checkmyipaddress.net</div>
    </div>
    <div class="command-box">
        <span class="command-label">Copy IPv6 Command (Soon):</span>
        <div class="command-url" onclick="copyCommand(this)">curl checkmyipaddress.net?mode=v6</div>
    </div>
</div>
    
    <h5>Copyright 2024 - © Noxity.io (Gostoljub d.o.o.) - All rights reserved.</h5>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="modehandler.js"></script>
<script>
document.getElementById("copy").onclick = function() {
    var e = document.getElementById("copy");
    if (document.body.createTextRange) {
        var t = document.body.createTextRange();
        t.moveToElementText(e), t.select(), document.execCommand("Copy");
        $("#copy").attr("aria-label", "Copied ✔️"), setTimeout(function() {
            $("#copy").attr("aria-label", "Click to copy 📋")
        }, 3000);
    } else if (window.getSelection) {
        var o = window.getSelection(), t = document.createRange();
        t.selectNodeContents(e), o.removeAllRanges(), o.addRange(t), document.execCommand("Copy");
        $("#copy").attr("aria-label", "Copied ✔️"), setTimeout(function() {
            $("#copy").attr("aria-label", "Click to copy 📋")
        }, 3000);
    }
};
</script>

<script>
    function copyCommand(element) {
        var text = element.textContent || element.innerText;
        var elem = document.createElement("textarea");
        document.body.appendChild(elem);
        elem.value = text;
        elem.select();
        document.execCommand("copy");
        document.body.removeChild(elem);

        // Trigger tooltip or visual feedback
        element.setAttribute('aria-label', 'Copied!');
        setTimeout(function() {
            element.setAttribute('aria-label', 'Click to copy 📋');
        }, 2000);
    }
</script>
</body>
</html>
<?php
}
?>
