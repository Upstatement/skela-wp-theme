![Logo](https://i.imgur.com/Qv18zRb.png)

# Skela

Skela is an opinionated but still fairly barebones WordPress theme. 93% of the work on this project was in choosing a name and logo.

It's currently only really intended for use by Upstatement and their best friends.

## 🎁 What It's Got

- Full [Timber](https://www.upstatement.com/timber/) integration, of course. ![Jaredzilla](https://dujrsrsgsd3nh.cloudfront.net/img/emoticons/42425/jaredzilla-1369410877.gif)
- Built in support for [Ups Dock](https://github.com/Upstatement/ups-dock), so you can get a full WordPress site up a running with a few commands
- Easily create documentation using [Flatdoc](http://ricostacruz.com/flatdoc/)
- A Github [pull request template](https://github.com/Upstatement/skela-wp-theme/blob/master/.github/pull_request_template.md)
- Repositories, managers, services and models for a very object oriented approach to organizing your WordPress data.
  - Managers do things like setup your theme (register option pages, hide dashboard widgets, enqueue JS and CSS), create custom post types and taxonomies, and setup basic WordPress defaults.
  - Models hold and extend your data. Have a press release post type that needs a bunch of extra functions? Create a class for them here, extending Timber\Post, and put your logic here so you can keep your Twig clean.
  - Repositories are a good place to put query related logic, let get me all the posts from September in the hot dog category.
  - Services are for more low lying functions, like routing.
- JS and SCSS in place, enqueued, and ready to be added to.
  - Includes an example of how to use [code splitting](https://webpack.js.org/guides/code-splitting/)
- Webpack via [Laravel Mix](https://github.com/JeffreyWay/laravel-mix) so you don't need to look at all the useless parts. Comes with:
  - Autoprefixer
  - [Vendor file extraction](https://laravel-mix.com/docs/2.1/extract)
  - Browsersync
  - Support for [code splitting](https://webpack.js.org/guides/code-splitting/)
- Linting!
  - For your SCSS linting, stylelint (see `.stylelintrc`)
  - For your Javascript code styles, prettier (see `.prettierrc`)
  - For your Javascript linting, eslint (see `.eslintrc.json`)
  - For your PHP linting, phpcs (see `phpcs.xml.dist`)
  - For automatic PHP lint fixing, phpcbf
- Testing!
  - Accessibility testing with [pa11y](https://github.com/pa11y/pa11y)
  - Bundle size limiting with [bundlesize](https://github.com/siddharthkp/bundlesize)
- [Husky](https://github.com/typicode/husky), to automatic run these lints and tests <img src="https://i.imgur.com/n9pF1TA.jpg" width="30px">
- WordPress plugin management via composer
- Some really great plugins
  - Advanced Custom Fields
  - WP Migrate DB Pro
  - WP Environment Indicator 😀
- Some useful PHP libraries
  - phpdotenv
  - carbon
  - whoops
- A sample setup for Travis (`.travis.yml`) with deployment (`scripts/deploy.sh`)

## 🏠 Making it your own

Do a **case-sensitive** search and replace for

- skela
- Skela
- SKELA

and replace those with your new and exciting theme name

## 🔨 Installation

### Local Environment

You first need a way to run a LAMP/LEMP (Linux, Apache/nginx, MySQL, PHP) stack on your machine.
At Upstatement we use a Docker setup neatly packed into something called Ups Dock. If you need to install that:

1. Install [Docker for Mac](https://www.docker.com/docker-mac)
2. Install [ups-dock](https://github.com/upstatement/ups-dock)

### Theme Setup

1. Ensure [NVM](https://github.com/creationix/nvm) and [NPM](https://www.npmjs.com/) are installed globally.

2. Run `nvm install` to ensure you're using the correct version of Node. If you are switching to projects with other versions of Node, we recommend using something like [Oh My Zsh](https://github.com/robbyrussell/oh-my-zsh) which will automatically run `nvm use`

3. Run `composer update`

4. Create a file called `.env` in this directory and populate it with configuration from the `scaffold - Dotenv (Local)` file in 1Password

5. If you would like to use the ACF and WP Migrate DB Pro plugins, you need to get the license keys (check 1Password) and paste them into `composer.json` where you see `ACF_KEY` and `WP_MIGRATE_KEY`. If not, remove these entries from the `repositories` and `require` sections

6. Run the install command:

   ```
   ./bin/install
   ```

Once completed, you should be able to access your WordPress installation via `ups.dock`. The default login is `admin / password`

If you need to SSH into your container, from your project root run `docker-compose exec wordpress /bin/bash`

## Development Workflow

1. Run `nvm use` to ensure you're using the correct version of Node
2. Run `./bin/start` to start the backend / static build server
3. Open the `Local` URL that appears below `[Browsersync] Access URLs:` in your browser (https://localhost:3000/)

Quitting this process (`Ctrl-C`) will shut down the container.

## wp-cli

If you've installed this using ups-dock, you can run wp-cli by typing `./wp-docker [command]`
