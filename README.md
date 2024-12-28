# TOC (Table of Contents) Plugin for Bludit CMS

The **TOC plugin** for **Bludit CMS** enhances the appearance of your articles by automatically generating a Table of Contents (TOC) based on the headings found within your content. This plugin is simple to use and easy to install.

## Features

- Automatically detects and generates a Table of Contents (TOC) based on the **h2 to h6** headings in your article.
- Customize the plugin to display only **h2** and **h3** headings if desired.
- The TOC will only be displayed if headings are found in your article. If no headings are present, the TOC will not be shown.

## Installation

1. Download or clone the repository.
2. Place the plugin in the `/bl-plugins` directory of your Bludit installation.
3. Activate the plugin from the **Bludit Dashboard**.

## How It Works

Once activated, the plugin will automatically:

- Scan your article for **h2-h6** headings.
- Generate a Table of Contents at the beginning of your content.
- Allow you to customize which heading levels (h2, h3, etc.) are displayed in the TOC.
- Don't forget to add ```php <?php Theme::plugins('siteBodyBegin'); ?> ```php right above <?php echo $page->content(); ?> in your "page.php" theme.

If no headings are found in your article, the TOC will not be displayed.

## Customization

You can customize the plugin to show only specific heading levels (e.g., h2 and h3) by modifying the plugin's configuration.

## License

This plugin is licensed under the MIT License.
