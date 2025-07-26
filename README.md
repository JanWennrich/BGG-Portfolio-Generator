# Jan's Boardgames 🎲

This is a static site generator for my boardgames hobby.

## Usage

### tl;dr

1. Run `composer install`
2. Run `make` (the `BGG_USERNAME` environment variable with your BoardGameGeek username has to be set)
3. Open `build/index.html` in your browser

(The deployment to GitHub pages is handled by the GitHub action in `.github/workflows/pages.yml`)

### Extensive information

The `download-bgg-data.php` script reads the [Board Game Geek XML API](https://boardgamegeek.com/wiki/page/BGG_XML_API2) to retrieve my profile data and stores that as serialized PHP objects on disk (see `assets/*.serialized.txt`)

The `generate-html.php` script unserializes the PHP objects and uses them to generate an HTML file in the `build/` directory.

The `build/` directory will be missing required assets such as CSS and JS files.

Use the `make` command to copy those into the `build/` directory.

Run `make download-bgg-data` to just download the data from the BGG API.  
Run `make generate-html` to just generate the HTML from the downloaded data (useful for faster development after the data was downloaded once).  
Run `make clean` to cleanup any builds.
