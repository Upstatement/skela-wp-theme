<p align="center">
  <img src="https://i.imgur.com/2GdqkHG.png" alt="Skela" style="display: block; margin: 0 auto">
<p>

# Skela

> Skela is an opinionated but still fairly barebones WordPress theme

Skela is an opinionated but still fairly barebones WordPress theme. Skela utilizes repositories, managers, services and models for a very object-oriented approach to organizing your WordPress data (more on that [here](#-object-oriented-approach)).

<a href="https://snyk.io/test/github/Upstatement/skela-wp-theme?targetFile=package.json"><img src="https://snyk.io/test/github/Upstatement/skela-wp-theme/badge.svg?targetFile=package.json" alt="Known Vulnerabilities" data-canonical-src="https://snyk.io/test/github/Upstatement/skela-wp-theme?targetFile=package.json" style="max-width:100%;"></a>

## Table of Contents

- [Skela](#skela)
  - [Table of Contents](#table-of-contents)
  - [🎁 What's in the Box](#-whats-in-the-box)
  - [💻 System Requirements](#-system-requirements)
  - [🛠 Installation](#-installation)
    - [Clone the repository](#clone-the-repository)
    - [Updating theme name](#updating-theme-name)
    - [ACF and WP Migrate DB Pro](#acf-and-wp-migrate-db-pro)
    - [Setup](#setup)
  - [🏃‍ Getting Started](#-getting-started)
    - [Development workflow](#development-workflow)
    - [Common wp-cli commands](#common-wp-cli-commands)
  - [🔄 Object-Oriented Approach](#-object-oriented-approach)
    - [Managers](#managers)
    - [Models](#models)
    - [Repositories](#repositories)
    - [Services](#services)
  - [📰 Gutenberg](#-gutenberg)
    - [Creating Custom ACF Blocks](#creating-custom-acf-blocks)
    - [Included Custom Blocks](#included-custom-blocks)
  - [🌐 Configuring Multisite](#-configuring-multisite)
  - [👨‍👩‍👧‍👦 Contributing](#-contributing)
  - [📗 Code of Conduct](#-code-of-conduct)
  - [About Upstatement](#about-upstatement)

## 🎁 What's in the Box

- Full [Timber](https://www.upstatement.com/timber/) integration
- Built-in support for [Ups Dock](https://github.com/Upstatement/ups-dock), so you can get a full WordPress site up a running with a few commands
- Easy documentation creation with [Flatdoc](http://ricostacruz.com/flatdoc/)
- Code bundling with [Webpack](https://webpack.js.org/), including:
  - [BrowserSync](https://www.npmjs.com/package/browser-sync-webpack-plugin)
  - [Autoprefixer](https://github.com/postcss/autoprefixer)
  - [CSS Extraction](https://www.npmjs.com/package/mini-css-extract-plugin)
  - [Environment Variable Injection](https://www.npmjs.com/package/dotenv-webpack)
- Some really great WordPress plugins (and plugin management provided by [Composer](https://getcomposer.org/))
  - [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/)
  - [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/)
  - [WP Environment Indicator](https://github.com/jon-heller/wp-environment-indicator)
- Some useful PHP libraries
  - [phpdotenv](https://github.com/vlucas/phpdotenv)
  - [carbon](https://carbon.nesbot.com/)
  - [whoops](https://github.com/filp/whoops)
- Linting and testing
  - JS, CSS, and PHP linting thanks to [Prettier](https://github.com/prettier/prettier), [ESLint](https://eslint.org/), and [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)
  - Accessibility testing with [pa11y](https://github.com/pa11y/pa11y)
  - Bundle size limiting with [bundlesize](https://github.com/siddharthkp/bundlesize)
  - [Husky](https://github.com/typicode/husky) to automatically run these lints and tests!
- CI setup for [Travis](https://travis-ci.com/) (with [deployment](scripts/deploy.sh))

## 💻 System Requirements

Before you can start on your theme, you first need a way to run a LAMP/LEMP (Linux, Apache/nginx, MySQL, PHP) stack on your machine.

We recommend our very own Docker setup, we neatly packed into something called Ups Dock. To install it follow these steps:

1. Install [Docker for Mac](https://www.docker.com/docker-mac)

2. Install [ups-dock](https://github.com/upstatement/ups-dock) (_Note that you can stop after the installation steps and come back to this README_)

## 🛠 Installation

### Clone the repository

This repository is _just_ for your WordPress theme. WordPress itself lives elsewhere.

If you are using [ups-dock](https://github.com/upstatement/ups-dock), you can clone this repository to anywhere (i.e. your `/Sites/`/ folder).

If you are using another local development solution, or if you're a madman and are cloning this directly to a live server, it might live in the `/wp-content/themes` directory.

### Updating theme name

In order to make this theme your own, do a **case-sensitive** search-and-replace of

- `skela`
- `Skela`
- `SKELA`

with your new and exciting theme name!

### ACF and WP Migrate DB Pro

If you would like to use the [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/) and [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/) plugins, use the following steps:

1. Purchase license keys from [ACF](https://www.advancedcustomfields.com/pro/#pricing-table) and [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/pricing/)

2. In `composer.json`, search-and-replace `ACF_KEY` and `WP_MIGRATE_KEY` with the respective license keys

_Note: if opting out of one or both of these plugins, **remove** the desired entries from the `repositories` and `require` sections in `composer.json`_

### Setup

1. Ensure [NVM](https://github.com/creationix/nvm) and [NPM](https://www.npmjs.com/) are installed globally.

2. Run `nvm install` to ensure you're using the correct version of Node.

   _Note: If you are switching to projects with other versions of Node, we recommend using something like [Oh My Zsh](https://github.com/robbyrussell/oh-my-zsh) which will automatically run `nvm use`_

3. Run `composer update`

4. If you're _not_ using ups-dock, you can stop here! Otherwise...

5. Copy the `.env.sample` into a new `.env` file

6. Run the install command:

   ```shell
   ./bin/install
   ```

Once completed, you should be able to access your WordPress installation via [`ups.dock`](http://ups.dock)!

If prompted for a login, the default in your `.env` file is `admin / password`

## 🏃‍ Getting Started

### Development workflow

1. Run `nvm use` to ensure you're using the correct version of Node

2. Run the start command to start the backend / static build server:

   ```shell
   ./bin/start
   ```

   Not using ups-dock? You can instead `npm run watch`

3. Open the `Local` URL that appears below `[Browsersync] Access URLs:` in your browser (https://localhost:3000/)

Quitting this process (`Ctrl-C`) will shut down the container.

### Common `wp-cli` commands

If you've installed this theme using `ups-dock`, you can run `wp-cli` by typing `./bin/wp [command]`.

Start the Docker containers with `./bin/start` and then run any of the following commands in a separate shell:

```shell
./bin/wp [command]
```

To export the database, use the following command:

```shell
./bin/wp db export - > dbdump.sql
```

To export the database and gzip it, use the following command:

```shell
./bin/wp db export - | gzip -3 > init.sql.gz
```

To SSH into the WordPress container, use the following command:

```shell
docker-compose exec wordpress /bin/bash
```

## 🔄 Object-Oriented Approach

Skela utilizes repositories, managers, services and models for a very object-oriented approach to organizing your WordPress data.

### Managers

Managers do things like:

- set up your theme (register option pages, hide dashboard widgets, enqueue JS and CSS, etc.)
- create custom post types and taxonomies
- set up basic WordPress defaults

### Models

Models hold and extend your data.

Have a press release post type that needs a bunch of extra functions? Create a class for them (extending `Timber\Post`) and put your logic there so you can keep your Twig clean!

### Repositories

Repositories are a good place to put query related logic.

It could be used in situations like the following:

> Let get me all the posts from September in the hot dog category!

### Services

Services are for more low-lying functions, like routing.

## 📰 Gutenberg

Skela has built-in support for easily creating custom Gutenberg blocks with the help of Advanced Custom Fields. Note that the pro version of ACF is required for this.

There is an example custom block under `src/Blocks/SampleACFBlock/ACFBlock.php`. This demonstrates creating a block using ACF functions that includes two fields. Those fields are rendered in the file `templates/components/acf-block.twig`.

Note that in order to get this example to work, you need to create a ACF field group containing two fields, `some_headline` and `some_text`, and then have the field group displayed if the block is equal to ACF block.

Read more details on [creating Gutenberg blocks using ACF](https://www.advancedcustomfields.com/resources/blocks/)

### Creating Custom ACF Blocks

1. Create a new ACF block class file in `/src/Blocks`.

   There is an example custom block under `src/Blocks/SampleACFBlock/ACFBlock.php`. This demonstrates creating a block using ACF functions that includes two fields.

   > Note that in order to get this example to work, you need to create an ACF field group containing two fields, `some_headline` and `some_text`, and then have the field group displayed if the block is equal to ACF Block.

2. Create a new twig file to render the ACF fields.

   The example block fields are rendered in the file `templates/components/acf-block.twig`.

3. Invoke the ACF block class in `/src/Blocks/Blocks.php`.

4. Add your new Gutenberg block to the array returned in the `allowBlocks` function in `/src/Managers/GutenbergManager.php`.

   ```php
    public function allowBlocks($allowed_blocks)
    {
        return array(
            ...
            'acf/acfBlock',
            ...
        );
    }
   ```

Read more about [creating Gutenberg blocks using ACF](https://www.advancedcustomfields.com/resources/blocks/)

### Included Custom Blocks

Two custom Gutenberg blocks are included with Skela:

- Related Articles
- Image Layout

These include basic styles so they can work out of the box. They _require_ the Advanced Custom Field plugin to function. If you do not plan to use ACF, you can disable these blocks by removing the applicable lines in the constructor function of `/src/Blocks/Blocks.php`

These fields are managed using PHP in the file `/src/Managers/ACFManager.php`. You can make updates to the fields here. If you would rather using the ACF to make updates to these fields:

1. Under `Advanced Custom Fields -> Tools`, import the JSON file in `/gutenberg-acf-backups/`
2. Make updates to the fields
3. Go to `Advanced Custom Fields -> Tools` and generate the PHP code
4. Update the PHP code in `/src/Managers/ACFManager.php`. Make sure to only update the PHP code for one layout group at a time, as they are separated by function in the manager file.

## 🌐 Configuring Multisite

If you wish to enable WordPress Multsite, consult [this guide](MULTISITE.md).

## 👨‍👩‍👧‍👦 Contributing

We welcome all contributions to our projects! Filing bugs, feature requests, code changes, docs changes, or anything else you'd like to contribute are all more than welcome! More information about contributing can be found in the [contributing guidelines](.github/CONTRIBUTING.md).

## 📗 Code of Conduct

Upstatement strives to provide a welcoming, inclusive environment for all users. To hold ourselves accountable to that mission, we have a strictly-enforced [code of conduct](CODE_OF_CONDUCT.md).

## About Upstatement

[Upstatement](https://www.upstatement.com/) is a digital transformation studio headquartered in Boston, MA that imagines and builds exceptional digital experiences. Make sure to check out our [services](https://www.upstatement.com/services/), [work](https://www.upstatement.com/work/), and [open positions](https://www.upstatement.com/jobs/)!
