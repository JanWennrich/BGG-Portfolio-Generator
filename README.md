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
composer generate [bgg_username]
```

Replace `[bgg_username]` with a BoardGameGeek username.

The generated portfolio will be located in the `public/` directory.
Open `public/index.html` in your browser to view it.

To keep your portfolio up to date, consider automating this command (e.g. via a daily CRON job or GitHub Action). See [Deployment](#deployment) below.

## 📦 Deployment

To publish your portfolio, deploy the contents of the `public/` directory to any static web host.

For continuous deployment, you can set up a CI/CD pipeline using GitHub Actions or other automation tools.

🛠️ *More deployment documentation coming soon.*

## 🤝 Contributing

Contributions are welcome! To get started:

* Fork the repository and create a feature branch
* Submit a pull request with a clear description of your changes
* Use [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) for commit messages
* Update documentation if your changes introduce new functionality

## 📄 License

This project is licensed under the **GNU General Public License v3.0 or later**. See the [`LICENSE`](LICENSE) file for details.
