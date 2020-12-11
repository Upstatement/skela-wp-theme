# 💀 Skela

> An Upstatement-flavored starter theme for WordPress

Skela utilizes repositories, managers, services, and models for an [object-oriented approach](#-object-oriented-approach) to organizing your WordPress data.

Note that this repository is _just_ for your WordPress theme. The WordPress installation should live elsewhere.

## Table of Contents

- [💀 Skela](#-skela)
  - [Table of Contents](#table-of-contents)
  - [🎁 What's in the Box](#-whats-in-the-box)
  - [💻 System Requirements](#-system-requirements)
  - [🛠 Installation](#-installation)
    - [Option 1: Contributing to Skela](#option-1-contributing-to-skela)
    - [Option 2: Using Skela as a template for another project](#option-2-using-skela-as-a-template-for-another-project)
    - [Activating ACF & WP Migrate Plugins (Optional)](#activating-acf--wp-migrate-plugins-optional)
  - [🏃‍ Development Workflow](#-development-workflow)
    - [Common `wp-cli` commands](#common-wp-cli-commands)
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
- Linting and testing
  - JS, CSS, and PHP linting thanks to [Prettier](https://github.com/prettier/prettier), [ESLint](https://eslint.org/), and [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)
  - Static analysis of PHP code with [PHPStan](https://phpstan.org/)
  - Accessibility testing with [pa11y](https://github.com/pa11y/pa11y)
  - Bundle size limiting with [bundlesize](https://github.com/siddharthkp/bundlesize)
  - [Husky](https://github.com/typicode/husky) to automatically run these lints and tests!
- CI setup with [GitHub Actions](https://help.github.com/en/actions)

## 💻 System Requirements

Before you can start on your theme, you first need a way to run a LAMP/LEMP (Linux, Apache/nginx, MySQL, PHP) stack on your machine.

We recommend our very own Docker setup called Ups Dock. To install it follow these steps:

1. Install [Docker for Mac](https://www.docker.com/docker-mac)

2. Install [Ups Dock](https://github.com/upstatement/ups-dock) by following the installation steps in the [README](https://github.com/upstatement/ups-dock#installation)

## 🛠 Installation

1. Ensure [NVM](https://github.com/creationix/nvm) and [NPM](https://www.npmjs.com/) are installed globally.

2. Run `nvm install` to ensure you're using the correct version of Node.

3. If you're _not_ using Ups Dock, you can stop here! Otherwise...

4. Duplicate the contents of `.env.sample` into a new `.env` file

### Option 1: Contributing to Skela

1. If you're installing this repository to contribute to Skela, all you need to do next is run the install command

   ```sh
   ./bin/install
   ```

2. Once the install script succeeds, fire up your site with the start command

   ```sh
   ./bin/start
   ```

   Now you should be able to access your WordPress site on [`ups.dock`](http://ups.dock)!

   The default credentials for WP admin are `admin` / `password` (configurable via `docker-compose.yml`)

### Option 2: Using Skela as a template for another project

If you're using Skela as a template for another project, there's a few more steps to go through in order to set up the project to use your desired theme name.

1. In `package.json` and `composer.json`, update repository and author information

2. Run the rename theme command and follow the prompt, which will set up the project with your desired theme name

   ```shell
   ./bin/rename-theme
   ```

3. Run the install command

   ```shell
   ./bin/install
   ```

4. Once the installation script has finished, run the start command

   ```shell
   ./bin/start
   ```

   Now you should be able to access your WordPress site on [`ups.dock`](http://ups.dock)!

   The default credentials for WP admin are `admin` / `password` (configurable via `docker-compose.yml`)

### Activating ACF & WP Migrate Plugins (Optional)

If you would like to use the [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/) and [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/) plugins, follow these steps:

1. Purchase license keys from [ACF](https://www.advancedcustomfields.com/pro/#pricing-table) and [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/pricing/)

2. In `composer.json` add the following to the `"repositories"` array

   ```text
   {
     "type": "package",
     "package": {
       "name": "advanced-custom-fields/advanced-custom-fields-pro",
       "version": "5.8.7",
       "type": "wordpress-plugin",
       "dist": {
         "type": "zip",
         "url": "https://connect.advancedcustomfields.com/index.php?a=download&p=pro&k=ACF_KEY&t=5.8.7"
       }
     }
   },
   {
     "type": "package",
     "package": {
       "name": "deliciousbrains/wp-migrate-db-pro",
       "type": "wordpress-plugin",
       "version": "1.9.10",
       "dist": {
         "type": "zip",
         "url": "https://deliciousbrains.com/dl/wp-migrate-db-pro-latest.zip?licence_key=WP_MIGRATE_KEY&site_url=SITE_URL?v=1.9.10"
       }
     }
   },
   {
     "type": "package",
     "package": {
       "name": "deliciousbrains/wp-migrate-db-pro-cli",
       "type": "wordpress-plugin",
       "version": "1.3.5",
       "dist": {
         "type": "zip",
         "url": "https://deliciousbrains.com/dl/wp-migrate-db-pro-cli-1.3.5.zip?licence_key=WP_MIGRATE_KEY&site_url=SITE_URL"
       }
     }
   },
   {
     "type": "package",
     "package": {
       "name": "deliciousbrains/wp-migrate-db-pro-media-files",
       "type": "wordpress-plugin",
       "version": "1.4.15",
       "dist": {
         "type": "zip",
         "url": "https://deliciousbrains.com/dl/wp-migrate-db-pro-media-files-latest.zip?licence_key=WP_MIGRATE_KEY&site_url=SITE_URL?v=1.4.15"
       }
     }
   }
   ```

3. Search and replace `ACF_KEY` and `WP_MIGRATE_KEY` with their respective license keys

4. Search and replace `SITE_URL` with your site's URL (i.e. `skela.ups.dock`)

5. In `composer.json` add the following to the `"require"` object

   ```json
   "advanced-custom-fields/advanced-custom-fields-pro": "5.8.7",
   "deliciousbrains/wp-migrate-db-pro": "1.9.10",
   "deliciousbrains/wp-migrate-db-pro-cli": "1.3.5",
   "deliciousbrains/wp-migrate-db-pro-media-files": "1.4.15",
   ```

6. While the container is up, run `./bin/composer install`

## 🏃‍ Development Workflow

1. Run `nvm use` to ensure you're using the correct version of Node

2. Run the start command to start the container and webpack server

   ```shell
   ./bin/start
   ```

   Not using Ups Dock? Run `npm run watch` instead

3. Visit the localhost URL in your browser

   By default this is https://localhost:3000/, which proxies your project's Ups Dock URL (i.e. https://skela.ups.dock)

4. Access the WP Admin Dashboard at `/wp-admin` (i.e. https://skela.ups.dock/wp-admin)

To shut down the container and development server, type `Ctrl+C`

### Common `wp-cli` commands

If you've installed this theme using Ups Dock, you can run `wp-cli` by typing `./bin/wp [command]`.

Start the Docker containers with `./bin/start` and then run any of the following commands in a separate shell:

```shell
./bin/wp [command]
```

To export the database, use the following command:

```shell
./bin/wp db export - > docker/conf/mysql/init.sql
```

To export the database and gzip it, use the following command:

```shell
./bin/wp db export - | gzip -3 > docker/conf/mysql/init.sql.gz
```

To SSH into the WordPress container, use the following command:

```shell
docker-compose exec wordpress /bin/bash
```

## 🔄 Object-Oriented Approach

This theme utilizes repositories, managers, services and models for a very object-oriented approach to organizing your WordPress data.

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

This theme has built-in support for easily creating custom Gutenberg blocks with the help of Advanced Custom Fields. Note that the pro version of ACF is required for this.

There is an example custom block under `src/Blocks/SampleACFBlock/ACFBlock.php`. This demonstrates creating a block using ACF functions that includes two fields. Those fields are rendered in the file `templates/components/acf-block.twig`.

Note that in order to get this example to work, you need to create a ACF field group containing two fields, `some_headline` and `some_text`, and then have the field group displayed if the block is equal to ACF block.

Read more details on [creating Gutenberg blocks using ACF](https://www.advancedcustomfields.com/resources/blocks/)

### Creating Custom ACF Blocks

1. Create a new ACF block class file in `/src/Blocks`.

   There is an example custom block under `src/Blocks/SampleACFBlock/ACFBlock.php`. This demonstrates creating a block using ACF functions that includes two fields.

   > Note that in order to get this example to work, you need to create an ACF field group containing two fields, `some_headline` and `some_text`, and then have the field group displayed if the block is equal to ACF Block. Be sure to keep your block name all lowercase. ACF drops all uppercase letters and your block might not appear as an option if the names are mismatched.

2. Create a new twig file to render the ACF fields.

   The example block fields are rendered in the file `templates/components/acf-block.twig`.

3. Invoke the ACF block class in `/src/Blocks/Blocks.php`.

4. Add your new Gutenberg block to the array returned in the `allowBlocks` function in `/src/Managers/GutenbergManager.php`.

   ```php
    public function allowBlocks($allowed_blocks)
    {
        return array(
            ...
            'acf/acf-block',
            ...
        );
    }
   ```

Read more about [creating Gutenberg blocks using ACF](https://www.advancedcustomfields.com/resources/blocks/)

### Included Custom Blocks

Two custom Gutenberg blocks are included:

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
