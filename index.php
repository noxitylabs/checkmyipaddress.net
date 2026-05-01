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

function getIpAddress() {
    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!array_key_exists($key, $_SERVER)) {
            continue;
        }
        $v6 = findIpInHeader($_SERVER[$key], FILTER_FLAG_IPV6);
        if ($v6 !== null) {
            return $v6;
        }
        $v4 = findIpInHeader($_SERVER[$key], FILTER_FLAG_IPV4);
        if ($v4 !== null) {
            return $v4;
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

if ($isV6) {
    $versionLabel = 'IPv6';
    $versionClass = 'v6';
} elseif ($isV4) {
    $versionLabel = 'IPv4';
    $versionClass = 'v4';
} else {
    $versionLabel = 'Unavailable';
    $versionClass = 'unavailable';
}

$displayIp = $ip !== '' ? $ip : 'No address detected';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#F7F5F0" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#131A28" media="(prefers-color-scheme: dark)">
    <title>Check My IP Address — Noxity</title>
    <meta name="description" content="A simple, honest tool from Noxity that shows your public IP address — IPv4 or IPv6 — with a one-line curl command for your terminal.">

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
            <span class="mark">N</span>
            <span class="word">Nox<em>it</em>y.</span>
        </a>
        <div class="nav-links">
            <a href="https://noxity.io">Hosting</a>
            <a href="#what-is-an-ip">What is an IP?</a>
            <a href="#terminal">Terminal</a>
        </div>
        <button id="themeToggle" class="theme-toggle" type="button" aria-label="Toggle color theme" title="Toggle color theme">
            <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
        </button>
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

                <button id="copy" class="ip-display" type="button" aria-label="Copy IP address">
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
                </button>

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
                        <p class="term-desc">Returns your IPv4 address as plain text &mdash; no HTML, no JSON, no headers <em>to fight</em>.</p>
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
                        <p class="term-desc">Force the response to your IPv6 address &mdash; falls back to <em>&ldquo;IPv6 Not available&rdquo;</em> when your network can&rsquo;t reach v6.</p>
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
