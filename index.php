<?php
function getIpAddress() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    return $ip;
                }
            }
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $ip;
                }
            }
        }
    }
    return '';
}

$ip = getIpAddress();
$isV6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
$isV4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;

if (stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'curl') !== false) {
    if (isset($_GET['mode']) && strtolower($_GET['mode']) === 'v6') {
        $ipv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        echo ($ipv6 === false ? "IPv6 Not available" : $ipv6) . "\n";
    } else {
        $ipv4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        echo ($ipv4 === false ? "IPv4 Not available" : $ipv4) . "\n";
    }
    exit;
}

$versionLabel = $isV6 ? 'IPv6' : ($isV4 ? 'IPv4' : 'Unavailable');
$versionClass = $isV6 ? 'v6' : ($isV4 ? 'v4' : 'unavailable');
$displayIp = $ip !== '' ? $ip : 'No address detected';
$year = date('Y');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Check My IP Address — Noxity</title>
    <meta name="description" content="A simple, honest tool from Noxity that shows your public IP address — IPv4 or IPv6 — with a one-line curl command for your terminal.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<header class="topbar">
    <nav class="pill-nav">
        <a class="brand" href="https://noxity.io" aria-label="Noxity">
            <span class="mark">N</span>
            <span class="word">Nox<em>it</em>y.</span>
        </a>
        <div class="nav-links">
            <a href="https://noxity.io">Hosting</a>
            <a href="#what-is-an-ip">What is an IP?</a>
            <a href="#terminal">Terminal</a>
        </div>
    </nav>
</header>

<main>
    <section class="hero">
        <div class="shell">
            <div class="hero-eyebrow"><span class="eyebrow">IP Lookup · honest &amp; ad-free</span></div>
            <h1>Your public<br><em>address</em>, plainly.</h1>
            <p class="hero-lead">No ads, no tracking, no fluff — just the IP your network is presenting to the world right <em>now</em>.</p>

            <div class="ip-stage">
                <div class="ip-stage-head">
                    <span class="label">Detected address</span>
                    <span class="ip-version <?php echo htmlspecialchars($versionClass); ?>"><?php echo htmlspecialchars($versionLabel); ?></span>
                </div>

                <div id="copy" class="ip-display" role="button" tabindex="0" aria-label="Copy IP address">
                    <span class="ip-text" id="ipText"><?php echo htmlspecialchars($displayIp); ?></span>
                    <span class="ip-hint" aria-hidden="true">
                        <span class="hint-idle">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                            <span>Click to copy</span>
                        </span>
                        <span class="hint-done">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12l4.5 4.5L20 6"/></svg>
                            <span>Copied</span>
                        </span>
                    </span>
                </div>

                <div class="ip-stage-foot">
                    <div class="meta">
                        <span><b>Protocol</b><?php echo htmlspecialchars($versionLabel); ?></span>
                        <span><b>Resolved</b>Live, server-side</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="what-is-an-ip">
        <div class="shell">
            <div class="section-head">
                <h2>What <em>is</em> an IP address?</h2>
                <p class="lead">The language computers speak when they pass packets across networks. Every device on your network — and every server you reach — is identified by one.</p>
            </div>
            <div class="section-body">
                <div>
                    <p>IPv4 addresses are four 8-bit octets — numbers from 0 to 255 — separated by periods. That gives roughly <em>4.29 billion</em> unique addresses. A lot, until you remember how many phones, fridges, and cameras are on the internet today.</p>
                    <p>So we&rsquo;re running out. Not in a panic-now way; in a planned-for-decades way.</p>
                </div>
                <div class="stat-card">
                    <span class="stat-eyebrow">The IPv6 successor</span>
                    <div class="stat-number">340 <em>undecillion</em></div>
                    <p class="stat-tag">Total addresses available with IPv6 — enough to assign one to every atom on Earth, with around 100 more Earths&rsquo; worth in reserve. Practically infinite.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="terminal">
        <div class="shell">
            <div class="section-head">
                <h2>Use it from your <em>terminal.</em></h2>
                <p class="lead">Hit the endpoint with curl and get just the address back &mdash; nothing else to parse, perfect for shell scripts and CI runs.</p>
            </div>

            <div class="term-grid">
                <article class="term-card v4">
                    <div class="term-tint">
                        <span class="term-eyebrow">IPv4</span>
                        <span class="term-tag"><em>default</em></span>
                    </div>
                    <div class="term-body">
                        <div class="term-prompt" role="button" tabindex="0" aria-label="Copy command">
                            <span class="dollar">$</span>
                            <span class="cmd">curl checkmyipaddress.net</span>
                            <span class="copy-icon idle" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                            </span>
                            <span class="copy-icon done" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M5 12l4.5 4.5L20 6"/></svg>
                                <span>Copied</span>
                            </span>
                        </div>
                        <p class="term-desc">Returns your IPv4 address as plain text &mdash; no HTML, no JSON, no headers <em>to fight</em>.</p>
                    </div>
                </article>

                <article class="term-card v6">
                    <div class="term-tint">
                        <span class="term-eyebrow">IPv6</span>
                        <span class="term-tag"><em>opt-in</em></span>
                    </div>
                    <div class="term-body">
                        <div class="term-prompt" role="button" tabindex="0" aria-label="Copy command">
                            <span class="dollar">$</span>
                            <span class="cmd">curl checkmyipaddress.net?mode=v6</span>
                            <span class="copy-icon idle" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                            </span>
                            <span class="copy-icon done" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M5 12l4.5 4.5L20 6"/></svg>
                                <span>Copied</span>
                            </span>
                        </div>
                        <p class="term-desc">Force the response to your IPv6 address &mdash; falls back to <em>&ldquo;IPv6 Not available&rdquo;</em> when your network can&rsquo;t reach v6.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="shell">
        <div class="foot-row">
            <span>&copy; <?php echo $year; ?> Noxity.io &middot; Gostoljub d.o.o.</span>
            <div class="foot-links">
                <a href="https://noxity.io">Noxity.io</a>
                <a href="https://github.com/Noxity-Network">GitHub</a>
                <a href="mailto:hello@noxity.io">hello@noxity.io</a>
            </div>
        </div>
    </div>
</footer>

<script>
(function () {
    function fallbackCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.top = '0';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(ta);
    }

    function writeClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text).catch(function () { fallbackCopy(text); });
        }
        fallbackCopy(text);
        return Promise.resolve();
    }

    var resetTimers = new WeakMap();

    function flashCopied(el) {
        el.classList.add('is-copied');
        var prev = resetTimers.get(el);
        if (prev) clearTimeout(prev);
        var timer = setTimeout(function () { el.classList.remove('is-copied'); }, 1800);
        resetTimers.set(el, timer);
    }

    function bindCopy(el, getText) {
        var doCopy = function (ev) {
            if (ev) ev.preventDefault();
            var text = getText();
            if (!text) return;
            writeClipboard(text);
            flashCopied(el);
        };
        el.addEventListener('click', doCopy);
        el.addEventListener('keydown', function (ev) {
            if (ev.key === 'Enter' || ev.key === ' ') doCopy(ev);
        });
    }

    var copyBtn = document.getElementById('copy');
    if (copyBtn) {
        var ipText = document.getElementById('ipText');
        bindCopy(copyBtn, function () {
            var v = ipText ? ipText.textContent.trim() : '';
            return (v && v !== 'No address detected') ? v : '';
        });
    }

    document.querySelectorAll('.term-prompt').forEach(function (el) {
        bindCopy(el, function () {
            var cmd = el.querySelector('.cmd');
            return cmd ? cmd.textContent.trim() : '';
        });
    });
})();
</script>
</body>
</html>
