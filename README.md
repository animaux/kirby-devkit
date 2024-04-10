# Kirby Devkit

Get easily accessible info on variables and data in frontend for template development.

![devkit](https://github.com/animaux/kirby-devkit/assets/446874/afcbba13-3d79-4810-a357-86e2ddf680d0)

## General

Coming from Symphony CMS I found myself a bit lost now and then when looking for available data while building page templates. Using the `debug devkit`-extension in Symphony there was a way to show all available parameters and data for a current page. This plugin aims to bring a bit of this back without having to resort to `var_dump()`s and echoing variables all too much.

## Installation

No composer registration yet. Simply copy the folder into you `site/plugins` folder.

## Usage

Simply add the query string `?dev` to your frontend page. If you already have a query string on your current site just add `&dev` instead. Also kirby’s debug mode needs to active.

### Configuration

Currently no configuration possible. However you can easily add/remove parameters and variables in `index.php`.

## Todo

Add Info on variables in controllers belonging to the current page/template. But how? 
