Theme Development Guide

Description

This document is intended to guide developers on how to develop and publish new themes for use with the InnoShop e-commerce system.

Publishing Themes

Use the following Artisan command to publish the theme in the innopacks/front directory as the default theme to /themes/default.

bash
php artisan inno:publish-theme
In the backend, under "Design - Template Theme List," select and activate the theme named default to easily switch to this theme.

Theme File Structure

If you need to revert changes to the theme template and restore the system's default template, you can do so by deleting the custom blade.php files in the views directory.

Once deleted, the system will automatically revert to using the original Blade templates in the innopacks/front directory for rendering.

CSS Styling
In the /css directory, you can organize multiple CSS or SCSS files to manage styles in a modular way.

JavaScript Scripts
The /js directory is used to store JavaScript files, which may include the theme's interactive logic and third-party libraries.

Public Static Resources
The /public directory is used to store the theme's static resources, which can be accessed via the web and are included in the blade with theme_asset().

Template Files
The /views directory contains the theme's Blade template files, which define the theme's layout and page structure.

Configuration File
config.json is the configuration file for the theme, shown in the following example:

json
{
    "code": "default",
    "name": {
        "zh_cn": "InnoShop Default Template",
        "en": "InnoShop Default Theme"
    },
    "description": {
        "zh_cn": "InnoShop Default Template",
        "en": "InnoShop Default Theme"
    },
    "version": "v1.0.0",
    "author": {
        "name": "InnoShop",
        "email": "team@innoshop.com"
    }
}
Compiling Custom Theme CSS and JS
In the webpack.mix.js file in the system's root directory, find the line const theme = ''; and replace the empty string with the name of your theme directory.

After completing this, execute the following command to compile your theme resources:

shell
npm run prod