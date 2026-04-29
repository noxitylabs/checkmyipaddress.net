# checkmyipaddress.net

A simple, honest tool that shows your public IP address — IPv4 or IPv6 — through a browser **or** straight from your terminal. No ads, no tracking, no fluff.

Operated by [Noxity.io](https://noxity.io) (Gostoljub d.o.o.).

---

## Features

- **Auto-detect** — picks IPv6 when your network has it, falls back to IPv4
- **Browser view** — editorial UI with one-click copy to clipboard
- **Terminal mode** — `curl` returns the address as plain text, no parsing needed
- **IPv4 / IPv6 explicit modes** — force the protocol you want from the command line
- **Privacy-first** — addresses are not logged or stored

---

## Usage

### Browser

Open the site:

```
https://checkmyipaddress.net
```

Click the IP card to copy it to your clipboard.

### Terminal

```bash
# IPv4 (default)
curl checkmyipaddress.net

# IPv6 (opt-in)
curl checkmyipaddress.net?mode=v6
```

If the requested protocol isn't available on your network, the response is `IPv4 Not available` or `IPv6 Not available`.

---

## Self-hosting

### Requirements

- PHP 8.0 or higher
- A web server (Apache, Nginx, Caddy, or PHP's built-in server for local dev)

### Install

```bash
git clone https://github.com/noxitylabs/checkmyipaddress.net.git
cd checkmyipaddress.net
```

Drop the contents into your web root, or run locally:

```bash
php -S 127.0.0.1:8001
```

### Reverse-proxy notes

If you sit behind a reverse proxy or CDN (Cloudflare, Caddy, Nginx, etc.), make sure the original client IP is forwarded via `HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`, or `REMOTE_ADDR` — those are the headers `getIpAddress()` walks, in that order.

---

## Design

The site uses the **Noxity Design System (v2)** — a Metronome-inspired editorial system pairing Manrope sans with Instrument Serif italic accents and JetBrains Mono eyebrows on a warm paper palette.

| Token | Hex |
|---|---|
| Ink | `#23375A` |
| Crimson | `#B83553` |
| Evergreen | `#4E7954` |
| Paper | `#F4F0E7` |
| Paper-2 | `#EFEBE3` |

Tokens live in `style.css`; see Noxity's design system bundle for the full system.

---

## Project structure

```
.
├── index.php     # Server-side IP detection + page markup + copy JS
├── style.css     # Design tokens + component styles
├── README.md
└── LICENSE.md
```

---

## License

See [LICENSE.md](LICENSE.md).
