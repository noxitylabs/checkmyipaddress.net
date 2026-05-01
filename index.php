<?php
function findIpInHeader($value, $flag) {
    foreach (explode(',', $value) as $candidate) {
        $candidate = trim($candidate);
        if (filter_var($candidate, FILTER_VALIDATE_IP, $flag)) {
            return $candidate;
        }
    }
    return null;
}

function detectAddresses() {
    $v4 = null;
    $v6 = null;
    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!array_key_exists($key, $_SERVER)) {
            continue;
        }
        if ($v4 === null) {
            $v4 = findIpInHeader($_SERVER[$key], FILTER_FLAG_IPV4);
        }
        if ($v6 === null) {
            $v6 = findIpInHeader($_SERVER[$key], FILTER_FLAG_IPV6);
        }
        if ($v4 !== null && $v6 !== null) {
            break;
        }
    }
    return ['v4' => $v4, 'v6' => $v6];
}

$addrs = detectAddresses();
$ipv4 = $addrs['v4'];
$ipv6 = $addrs['v6'];

if (stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'curl') !== false) {
    if (isset($_GET['mode']) && strtolower($_GET['mode']) === 'v6') {
        echo ($ipv6 ?? 'IPv6 Not available') . "\n";
    } else {
        echo ($ipv4 ?? 'IPv4 Not available') . "\n";
    }
    exit;
}

$primaryIp = $ipv4 ?? $ipv6;
$secondaryIp = $ipv4 !== null ? $ipv6 : null;

if ($primaryIp === null) {
    $primaryLabel = 'Unavailable';
    $primaryClass = 'unavailable';
    $primaryDisplay = 'No address detected';
} elseif ($primaryIp === $ipv4) {
    $primaryLabel = 'IPv4';
    $primaryClass = 'v4';
    $primaryDisplay = $primaryIp;
} else {
    $primaryLabel = 'IPv6';
    $primaryClass = 'v6';
    $primaryDisplay = $primaryIp;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#F7F5F0" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#131A28" media="(prefers-color-scheme: dark)">
    <title>Check My IP Address - Noxity</title>
    <meta name="description" content="Shows your public IP address (IPv4 and IPv6) with a curl command for the terminal. Made by Noxity.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="style.css" rel="stylesheet" type="text/css">

    <script>
    (function () {
        try {
            var saved = localStorage.getItem('cmip-theme');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        } catch (e) {}
    })();
    </script>
</head>
<body>

<header class="topbar">
    <nav class="pill-nav">
        <a class="brand" href="https://noxity.io" aria-label="Noxity">
            <img class="brand-logo" src="noxity-logo.png" alt="" width="28" height="28">
            <span class="brand-word">Noxity</span>
        </a>
        <button id="themeToggle" class="theme-toggle" type="button" aria-label="Toggle color theme" title="Toggle color theme">
            <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
        </button>
    </nav>
</header>

<main>
    <section class="hero">
        <div class="shell">
            <div class="hero-eyebrow"><span class="eyebrow">Public IP lookup</span></div>
            <h1>Your public<br><em>IP address</em>.</h1>
            <p class="hero-lead">No ads or tracking. The IP your network is showing the rest of the internet right <em>now</em>.</p>

            <div class="ip-stage">
                <div class="ip-stage-head">
                    <span class="label">Detected address</span>
                    <span class="ip-version <?php echo htmlspecialchars($primaryClass); ?>"><?php echo htmlspecialchars($primaryLabel); ?></span>
                </div>

                <button id="copy" class="ip-display" type="button" aria-label="Copy <?php echo htmlspecialchars($primaryLabel); ?> address">
                    <span class="ip-text" id="ipText"><?php echo htmlspecialchars($primaryDisplay); ?></span>
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
                </button>

                <?php if ($secondaryIp !== null): ?>
                <button class="ip-secondary" type="button" aria-label="Copy IPv6 address">
                    <span class="ip-secondary-tag">IPv6</span>
                    <span class="ip-secondary-text" id="ipv6Text"><?php echo htmlspecialchars($secondaryIp); ?></span>
                    <span class="ip-secondary-hint" aria-hidden="true">
                        <span class="hint-idle">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                        </span>
                        <span class="hint-done">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12l4.5 4.5L20 6"/></svg>
                        </span>
                    </span>
                </button>
                <?php endif; ?>

                <div class="ip-stage-foot">
                    <div class="meta">
                        <span><b>Protocol</b><?php echo htmlspecialchars($primaryLabel); ?><?php echo $secondaryIp !== null ? ' + IPv6' : ''; ?></span>
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
                <p class="lead">The language that computers use to transmit data packets across networks is called Internet Protocol. On your home/office network or the internet, your computer, mobile device, or appliance is identified by its IP address.</p>
            </div>
            <div class="section-body">
                <div>
                    <p>Four 8-bit octets (0 to 255) separated by a period make up IP addresses. There are about 4,294,967,296 addresses that may be used since this creates a 32-bit numeric address. Astonishingly, they will soon run out.</p>
                </div>
                <div class="stat-card">
                    <span class="stat-eyebrow">IPv6</span>
                    <div class="stat-number">340 <em>undecillion</em></div>
                    <p class="stat-tag">Don&rsquo;t freak out though! The IPv6 protocol was already developed by the researchers as a replacement. Having the ability to support 340,282,366,920,938,463,463,374,607,431,768,211,456 addresses, which is enough to give every atom on Earth an IP address. And have enough for around 100 more Earths.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="terminal">
        <div class="shell">
            <div class="section-head">
                <h2>Use it from your <em>terminal.</em></h2>
                <p class="lead">Hit the endpoint with curl. You get back the address as plain text, ready for shell scripts and CI.</p>
            </div>

            <div class="term-grid">
                <article class="term-card v4">
                    <div class="term-tint">
                        <span class="term-eyebrow">IPv4</span>
                        <span class="term-tag"><em>default</em></span>
                    </div>
                    <div class="term-body">
                        <button class="term-prompt" type="button" aria-label="Copy command">
                            <span class="dollar">$</span>
                            <span class="cmd">curl checkmyipaddress.net</span>
                            <span class="copy-icon idle" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                            </span>
                            <span class="copy-icon done" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M5 12l4.5 4.5L20 6"/></svg>
                                <span>Copied</span>
                            </span>
                        </button>
                        <p class="term-desc">Returns your IPv4 address as plain text. Nothing else <em>to parse</em>.</p>
                    </div>
                </article>

                <article class="term-card v6">
                    <div class="term-tint">
                        <span class="term-eyebrow">IPv6</span>
                        <span class="term-tag"><em>opt-in</em></span>
                    </div>
                    <div class="term-body">
                        <button class="term-prompt" type="button" aria-label="Copy command">
                            <span class="dollar">$</span>
                            <span class="cmd">curl checkmyipaddress.net?mode=v6</span>
                            <span class="copy-icon idle" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
                            </span>
                            <span class="copy-icon done" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M5 12l4.5 4.5L20 6"/></svg>
                                <span>Copied</span>
                            </span>
                        </button>
                        <p class="term-desc">Forces the response to your IPv6 address. Falls back to <em>&ldquo;IPv6 Not available&rdquo;</em> if your network can&rsquo;t reach v6.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>
</main>

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

    document.querySelectorAll('.ip-secondary').forEach(function (el) {
        var t = el.querySelector('.ip-secondary-text');
        bindCopy(el, function () { return t ? t.textContent.trim() : ''; });
    });

    document.querySelectorAll('.term-prompt').forEach(function (el) {
        bindCopy(el, function () {
            var cmd = el.querySelector('.cmd');
            return cmd ? cmd.textContent.trim() : '';
        });
    });

    var themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        var setTheme = function (theme) {
            document.documentElement.setAttribute('data-theme', theme);
            try { localStorage.setItem('cmip-theme', theme); } catch (e) {}
            themeBtn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        };
        var current = document.documentElement.getAttribute('data-theme') || 'light';
        themeBtn.setAttribute('aria-pressed', current === 'dark' ? 'true' : 'false');
        themeBtn.addEventListener('click', function () {
            var next = (document.documentElement.getAttribute('data-theme') === 'dark') ? 'light' : 'dark';
            setTheme(next);
        });

        if (window.matchMedia) {
            var mq = window.matchMedia('(prefers-color-scheme: dark)');
            var handler = function (e) {
                try {
                    if (!localStorage.getItem('cmip-theme')) {
                        setTheme(e.matches ? 'dark' : 'light');
                    }
                } catch (err) {}
            };
            if (mq.addEventListener) mq.addEventListener('change', handler);
            else if (mq.addListener) mq.addListener(handler);
        }
    }
})();
</script>
</body>
</html>
