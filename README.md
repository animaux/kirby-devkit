# Kirby Devkit

Get easily accessible info on variables and data in frontend for template development.

## General

Coming from Symphony CMS I found myself a bit lost now and then when looking for available data while building page templates. Using the `debug devkit`-extension in Symphony there was a way to show all available parameters and data for a current page. This plugin aims to bring a bit of this back without having to resort to `var_dump()`s and echoing variables all too much.

## Installation

No composer registration yet. Simply copy the folder into you `site/plugins` folder.

## Usage

Simply add the query string `?dev` to your frontend page. If you already have a query string on your current site just add `&dev` instead.

### Configuration

Currently no configuration possible. However you can easily add/remove parameters and variables in `index.php`.
