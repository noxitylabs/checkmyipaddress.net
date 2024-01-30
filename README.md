# CheckMyIPAddress.net

checkmyipaddress.net is a simple web utility designed to display the current IP address of the user accessing the webpage. It's a simple and effective way to allow users to check their public IP address. The script determines the IP address of the user based on various server variables and displays it in a user-friendly format.

***

## Table of Contents

* [Getting Started](#getting-started)
* [Prerequisites](#prerequisites)
* [Installation](#installation)
* [Usage](#usage)
* [Contributing](#contributing)
* [Roadmap](#roadmap)
* [Acknowledgements](#acknowledgements)

***

## Getting Started

### Prerequisites

- A web server with PHP support (e.g., Apache, Nginx).
- PHP 7.0 or higher (PHP 8.0+ recommended).

### Installation

1. Clone the repository or download the zip file.
2. Place the contents in your web server's (root) directory.

### Usage

- Navigate to the index page of where you uploaded the files using a web browser.
- The page will automatically display your current IP address.
- For CLI users, accessing the page with `curl https://checkmyipaddress.net` will return a plain IP address string.

***

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

***

## Roadmap

- [ ] Add support for choosing the IPv4/IPv6 or default route via CLI tool.
- [ ] Always default to displaying IPv4 address on the web page if available.
- [ ] Add additional data about the IP address such as ISP information, geolocation, etc.

***

## Acknowledgements

- This script was inspired by common needs for IP address detection in web applications.
- Thanks to the PHP community for the continuous support and feedback.
