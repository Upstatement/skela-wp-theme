# 🌐 Configuring Multisite with Skela

- [🌐 Configuring Multisite with Skela](#-configuring-multisite-with-skela)
  - [📚 Step 0: Recommended Reading](#-step-0-recommended-reading)
  - [✅ Step 1: Enable Multisite](#-step-1-enable-multisite)
  - [🖋 Step 2: Install your WordPress network](#-step-2-install-your-wordpress-network)
  - [🛠 Step 3: Configure Scripts and Nginx](#-step-3-configure-scripts-and-nginx)
  - [➕ Step 4: Add a site to your network](#-step-4-add-a-site-to-your-network)
  - [📤 Step 5: Export your local database](#-step-5-export-your-local-database)
  - [🧐 Troubleshooting](#-troubleshooting)

## 📚 Step 0: Recommended Reading

- [WordPress Docs: Create A Network](https://wordpress.org/support/article/create-a-network/)
- [WordPress Docs: Before You Create A Network](https://wordpress.org/support/article/before-you-create-a-network/)

## ✅ Step 1: Enable Multisite

Multisite comes built-in with your installation of WordPress, but it’s not enabled by default. To enable it, you must first define multisite in your `wp-config.php`.

In a regular WordPress installation, `wp-config.php` would be on your local machine. However, since your [Ups Dock](https://github.com/Upstatement/ups-dock) setup keeps WordPress files isolated in a container, you'll need to modify the file within the docker container.

With the container running in your terminal (`./bin/start`), open another terminal window and run the following command:

```shell
docker-compose exec wordpress /bin/bash
```

This will give you access to all of the files in your Docker container. To open the file `wp-config.php` file with Vim, type:

```shell
vi wp-config.php
```

Right **above** the line where it says `/* That's all, stop editing! Happy publishing. */`, paste the following:

```php
/* Multisite */
define('WP_ALLOW_MULTISITE', true);
```

Then save and quit Vim (`:wq`) and exit the docker container by typing `exit`.

> NOTE: This step is important to do first, _before_ inserting the code in the next step and running `./bin/start`. If you run before enabling Multisite, it will not work and you cannot revert. If this happens, refer to the [Troubleshooting](#-troubleshooting) section to destroy the Docker container and start over.

**If you plan on using sub-domains as your domain structure,** change the `VIRTUAL_HOST` value in your `.env` file:

```shell
VIRTUAL_HOST=YOURSITENAME.ups.dock,*.YOURSITENAME.ups.dock
```

Don’t forget to replace **YOURSITENAME** with the name of your project.

## 🖋 Step 2: Install your WordPress network

If your container is not already running, start the server by running `./bin/start`.

In your browser, go to the WP dashboard at **YOURSITENAME**.ups.dock/wp-admin. It will prompt you to log in. The default credentials are: `admin` // `password`

Before proceeding, you’ll first need to disable all plugins. Visit the **Plugins » Installed Plugins** page and deactivate all plugins.

Then, navigate to the **Tools » Network Setup** page to configure your multisite network.

![Create a Network of WordPress Sites page](https://i0.wp.com/wordpress.org/support/files/2018/11/network-create.png?fit=1024%2C743&ssl=1)

Next, you need to tell WordPress what kind of domain structure you will be using for your network’s sites: sub-domains (`site1.upstatement.com`) or sub-directories(`upstatement.com/site`).

Provide a title for your network and make sure that the email address in the Network Admin Email field is correct.

Click the `Install` button to continue.

> NOTE: After selecting your domain structure, you cannot change it. If you wish to change your domain structure, refer to the [Troubleshooting](#-troubleshooting) section to destroy the Docker container and start over.

## 🛠 Step 3: Configure Scripts and Nginx

WordPress will then provide you some code you need to add to your files in order to enable your network.

![Enabling the network page](https://i0.wp.com/wordpress.org/support/files/2018/11/tools-network-created.png?fit=1024%2C742&ssl=1)

You can ignore the instructions in the second part regarding `.htaccess` because WordPress assumes you are using an Apache server. Skela uses [nginx](https://www.nginx.com/), not Apache.

Next, open your Skela project in a code editor.

In `scripts/setup.sh`, paste the following code block **below** the line says `define('WP_DEBUG', true);`

```php
/* Multisite */
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'YOURSITENAME.ups.dock');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
```

Don’t forget to replace **YOURSITENAME** with the name of your project.

Notice that you’re not directly modifying `wp-config.php` in the Docker container this time — instead you're adding this code block to the setup script. This is because this script is run when you set up your docker container (`./bin/install`), which we'll be doing shortly. The code block above will automatically be added to `wp-config.php`.

Next, in `conf/nginx/nginx-site.conf`, locate the line where it says `# Override base location to work with WordPress pretty permalinks`. Right **above** this line, paste the following block:

```conf
# WordPress Multisite Subdirectory Rules
# https://wordpress.org/support/article/nginx/#wordpress-multisite-subdirectory-rules
if (!-e $request_filename) {
  rewrite /wp-admin$ $scheme://$host$request_uri/ permanent;
  rewrite ^(/[^/]+)?(/wp-.*) $2 last;
  rewrite ^(/[^/]+)?(/.*\.php) $2 last;
}
```

This config will allow nginx to play nice with your subdirectory-based multisite network.

Save the files and re-run `./bin/start`.

After this, you will need to log out and log back into the WordPress dashboard to access multisite.

## ➕ Step 4: Add a site to your network

The best way to ensure that multisite is working properly is to add a new site to your network. To add a new site, navigate to **My Sites » Network Admin » Sites** from the house icon in the top left corner. This will show you a list of sites on your current installation.

![Sites page](https://i0.wp.com/wordpress.org/support/files/2018/11/network-admin-link.png?fit=383%2C184&ssl=1)

To add a new site, click on the `Add New` button at the top.

On the `Add New Site` page, provide the site’s address. You don’t need to type the full address, just the part you want to use as subdomain or sub-directory.

Add a site title, and enter the site admin’s email address. Click on the `Add Site` button in the bottom left when you’re done.

Make sure you’re able to visit your new site’s home page and WordPress dashboard without error.

## 📤 Step 5: Export your local database

In order for others working on your project to easily get multisite up and running in their environments, you can export your local WordPress database and commit it to source control to be used upon project installation.

From the terminal, run the following command:

```shell
./bin/wp db export - | gzip -3 > init.sql.gz
```

This will export your local WordPress database as a gzipped SQL file at the root of your project. Move this file into the `conf/mysql` directory in your project. You may need to create the `mysql` folder if it’s not already there.

Then, uncomment line 21 of `docker-compose.yml` to add the initial dump file to your project installation process.

## 🧐 Troubleshooting

If things go wrong and you need to start over with your Docker container, run the following command in your terminal to destroy your Docker container.

```shell
docker-compose down -v --remove-orphans
```

Then, reinstall with `./bin/install`.

Make sure to remove the offending code/files/etc. first, as this will not delete them. It just removes the container.
