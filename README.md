# BoardGameGeek Portfolio Generator 🎲

A static site generator that fetches a BoardGameGeek (BGG) user's data and compiles it into a human-readable, self-contained HTML portfolio. Perfect for showcasing your board game collection online or archiving it offline.

## ✨ Features

* Presents key information from your BGG profile, organized in interactive tabs:
    * **Play History** – grouped and sorted by date
    * **Wishlist** – grouped and sorted by priority
    * **Collection** – grouped and sorted alphabetically
* Displays games as box cover thumbnails with direct links to their BGG pages
* Supports deep linking to specific tabs and sections
* Fully static output: no backend required (vanilla JavaScript, HTML, and CSS)

## 🔧 Prerequisites

* PHP 8.3 or later (CLI enabled)
* [Composer](https://getcomposer.org)

## 🚀 Installation & Setup

```bash
composer create-project jan-wennrich/bgg-portfolio-generator
cd bgg-portfolio-generator/
```

## 🛠️ Usage

Generate your portfolio by running:

```bash
php bin/bgg-portfolio-generator.php generate [bgg_username] [--bgg-token/--bgg-password]
```

Replace `[bgg_username]` with a BoardGameGeek username.

You have to authenticate via:
- API token (recommended): `--bgg-token=<api-token>` (replace `<api-token>` with your API token)  
- Password (workaround): `--bgg-password=<password>` (replace `<password>` with the password of the `bgg_username`)

_(More information about authentication methods in the ["Authentication"-section](#-authentication) below)_

Example command:

```bash
php bin/bgg-portfolio-generator.php generate Klabauterjan --bgg-token="123-foo-bar-456"
```

The generated portfolio will be located in the `public/` directory.  
Open `public/index.html` in your browser to view it.

To keep your portfolio up to date, consider automating this command (e.g. via a daily CRON job or GitHub Action).  
_(More information about automation in the ["Deployment"-section](#-deployment) below)_

## 🔑 Authentication

Recently the BoardGameGeek API requires authentication to access the data required to generate a portfolio.

You can either authenticate via an [API token](https://boardgamegeek.com/using_the_xml_api) or your BoardGameGeek password as a workaround.

The password workaround limits the functionality of the portfolio generator (e.g. loading thumbnails for plays does not work).  
Thus using an API token is the recommended way.  
Follow this link to obtain an API token: https://boardgamegeek.com/using_the_xml_api

## 📦 Deployment

To publish your portfolio, deploy the contents of the `public/` directory to any static web host.

You can utilize the [BGG Portfolio Generator Action](https://github.com/marketplace/actions/bgg-portfolio-generator) to deploy your portfolio to GitHub Pages for free. Refer to its usage example to get started.


## 🤝 Contributing

Contributions are welcome! To get started:

* Fork the repository and create a feature branch
* Submit a pull request with a clear description of your changes
* Use [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) for commit messages
* Update documentation if your changes introduce new functionality

## 📄 License

This project is licensed under the **GNU General Public License v3.0 or later**. See the [`LICENSE`](LICENSE) file for details.
