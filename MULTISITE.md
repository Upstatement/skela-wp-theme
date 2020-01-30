# 🌐 Configuring Multisite
## 🔗 Limitations

Currently, Skela only supports Multisite with subdirectories. If you must use subdomains, Skela is the wrong system for you. 



## ✅ Enable Multisite

Multisite comes built-in with your installation of WordPress, but it’s not enabled by default. To enable it, you must first define multisite in your `wp-config.php`.

In a regular WordPress installation, `wp-config.php` would be on your local machine. However, since your Ups Dock setup keeps WordPress files isolated in a container, we’ll have to modify the file within the docker container. 

With the container running in your terminal (`./bin/start`), open another terminal window and run the following command:


    docker-compose exec wordpress /bin/bash

This will give you access to all of the files in your Docker container. To open the file `wp-config.php` file with Vim, type:


    vi wp-config.php

Scroll down to the line where it says `**/* That's all, stop editing! Happy publishing. */**` ****Right **above** this line, paste the following:


    /* Multisite */
    define('WP_ALLOW_MULTISITE', true);                                                                       

Then save and quit Vim (`:wq`) and exit the docker container by typing `exit`.


----------

* This step is important to do first, *before* inserting the code in the next step and running `./bin/start`. If you run before enabling Multisite, it will not work and you cannot revert. If this happens, refer to the **Troubleshooting** section to destroy the Docker container and start over.



## 🖋 Install your WordPress network

Now that Multisite is enabled, we can actually set up your multisite network through WordPress’s Network Setup page.

If your container is not already running, start the server by running  `./bin/start`. 

In your browser, go to **YOURSITENAME**.ups.dock/wp-admin. It will prompt you to log in. The default credentials are:

    username: admin
    password: password

You’ll first need to disable all plugins that are currently activated. Visit the **Plugins » Installed Plugins** page and select all plugins. Select ‘Deactivate’ from the ‘Bulk Actions’ dropdown menu and then click the ‘Apply’ button.

Then, Navigate to the **Tools » Network Setup** page to configure your multisite network. 


![Create a Network of WordPress Sites page](https://i0.wp.com/wordpress.org/support/files/2018/11/network-create.png?fit=1024%2C743&ssl=1)



Next, you need to tell WordPress what kind of domain structure you will be using for your network’s sites: sub-domains (`site1.upstatement.com`) or sub-directories(`upstatement.com/site`). Currently, Skela only supports subdirectories. Select subdirectories.

Provide a title for your network and make sure that the email address in the Network Admin Email field is correct.

Click the `Install` button to continue. 


----------

*After selecting your domain structure, you cannot change it. If you wish to change your domain structure, refer to the **Troubleshooting** section to destroy the Docker container and start over.



## 🛠 Configure Scripts and Nginx 

WordPress will then provide you some code you need to add to your files in order to enable your network. (You only need to pay attention to the first part — the instructions for `wp-config.php`. You can ignore the instructions for `.htaccess` because WordPress assumes you are using an Apache server. However, ups dock sets you up with [nginx](https://www.nginx.com/), not apache.)

Open the project in a code editor. 

In `scripts/setup.sh`, paste the following code block **below** the line says `define('WP_DEBUG', true);`


    /* Multisite */
     define('MULTISITE', true);
     define('SUBDOMAIN_INSTALL', false);
     define('DOMAIN_CURRENT_SITE', 'YOURSITENAME.ups.dock');
     define('PATH_CURRENT_SITE', '/');
     define('SITE_ID_CURRENT_SITE', 1);
     define('BLOG_ID_CURRENT_SITE', 1);

Don’t forget to change **YOURSITENAME** to the name of your project.

Notice you’re not directly modifying `wp-config.php` in the docker container this time — we’re adding this code block to the setup script, because we will be re-installing our containers once we’re done setting up multisite, and docker will run the setup script when it brings up the containers again. The code block above will automatically be added to `wp-config.php`.


Next, in `conf/nginx/nginx-site.conf`, locate the line where it says `**# Override base location to work with WordPress pretty permalinks**`**.** Right **before** this line, paste the following block:


    # WordPress Multisite Subdirectory Rules   
    # https://wordpress.org/support/article/nginx/#wordpress-multisite-subdirectory-rules      
    if (!-e $request_filename) {          
      rewrite /wp-admin$ $scheme://$host$request_uri/ permanent;          
      rewrite ^(/[^/]+)?(/wp-.*) $2 last;          
      rewrite ^(/[^/]+)?(/.*\.php) $2 last;      
    }

This config will allow nginx to play nice with your subdirectory-based multisite network.

Save the files and rerun `./bin/start`.

After this, you will need to log out and log back into WordPress to access multisite.



## ➕ Add Sites

The best way to ensure that WordPress multisite is working properly is to add a new site to your network!

To add a new site, navigate to **My Sites » Network Admin » Sites** from the house icon in the top left corner. This will show you a list of sites on your current installation.


![](https://paper-attachments.dropbox.com/s_E2E20ECA9384D2EC84AC7E267F57CE88F99A1E729B7D89FCC58370D717AC0FD7_1580360656682_image.png)


To add a new site, click on the `Add New` button at the top.

On the `Add New Site` page, provide the site’s address. You don’t need to type the full address, just the part you want to use as subdomain or sub-directory.

Add a site title, and enter the site admin’s email address. Click on the `Add Site` button in the bottom left when you’re done.

Make sure you’re able to visit your new site’s home page and WordPress dashboard without error.



## ➡️ Export the Database

In order for others working on your project to easily get multisite up and running in their environments, you can export your WordPress database and commit it to source control to be used upon project installation.

From the terminal, run the following line:


    ./bin/wp db export - | gzip -3 > init.sql.gz

This will export your local WordPress database as a gzipped SQL file at the root of your project. Move this file into the  `conf/mysql` directory in your project. You may need to create the `mysql` folder if it’s not already there.



# 🧐 Troubleshooting

If things go wrong and you need to start over with your Docker container, run the following line from your terminal to destroy your Docker container.


    docker-compose down -v --remove-orphans

Then, reinstall with `./bin/install`. 

Make sure to remove the offending code/files/etc. first, as this will not delete them. It just removes the container.

