#!/bin/bash

set -e

WORDPRESS_MULTISITE=${WORDPRESS_MULTISITE:-false}
WORDPRESS_MULTISITE_SUBDOMAIN_INSTALL=${WORDPRESS_MULTISITE_SUBDOMAIN_INSTALL:-false}

# Ensure MySQL connection is up before proceeding.
until mysql -uwordpress -pwordpress -hdb wordpress; do
  >&2 echo "Waiting for MySQL ..."
  sleep 1
done

# Generate wp-config.php
echo "Configuring WordPress..."
wp config create \
  --dbname=$WORDPRESS_DB_NAME \
  --dbuser=$WORDPRESS_DB_USER \
  --dbpass=$WORDPRESS_DB_PASSWORD \
  --dbhost=$WORDPRESS_DB_HOST \
  --extra-php <<PHP
// This allows the WordPress site to run securly behind the ups-dock reverse proxy
if (strpos(\$_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
  \$_SERVER['HTTPS'] = 'on';
}

/* Set home / site URL based on incoming request for local development to avoid SSL headaches */
\$schema = \$_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
\$host = parse_url('$WORDPRESS_URL', PHP_URL_HOST);
\$port = parse_url('$WORDPRESS_URL', PHP_URL_PORT);
\$path = parse_url('$WORDPRESS_URL', PHP_URL_PATH);
\$port = \$port ? ":\${port}" : '';
\$url = "\$schema://\${host}\${port}\${path}";
define('WP_HOME',    \$url);
define('WP_SITEURL', \$url);

/* Logging */
define('WP_DEBUG_LOG', '/var/www/html/logs/error.log');
define('WP_DEBUG_DISPLAY', false);
PHP

echo "Setting WP_DEBUG to true..."
wp config set WP_DEBUG true --raw

# Run WP install if it's not already installed.
if ! wp core is-installed; then
  install_args=(
      --url="$WORDPRESS_URL"
      --title="$WORDPRESS_TITLE"
      --admin_user="$WORDPRESS_ADMIN_USER"
      --admin_email="$WORDPRESS_ADMIN_EMAIL"
      --admin_password="$WORDPRESS_ADMIN_PASSWORD"
      )

  # Fresh multisite install
  if [ $WORDPRESS_MULTISITE = 1 ]; then
    if [ $WORDPRESS_MULTISITE_SUBDOMAIN_INSTALL = 1 ]; then
      install_args+=(--subdomains)
    fi

    echo "Installing WordPress Multisite..."
    wp core multisite-install "${install_args[@]}"
  else
    echo "Installing WordPress..."
    wp core install "${install_args[@]}"
  fi

  # Ensure correct permissions for files / directories creating during install.
  if [ -z "$SKIP_CHOWN" ]; then
    chown -Rf nginx.nginx /var/www/html
  fi
fi

# Convert from existing single site to multisite instsall
ms_tables=$(wp db tables --scope=ms_global)
if [ $WORDPRESS_MULTISITE = 1 ] && [ ! "$ms_tables" ]; then
  if [ $WORDPRESS_MULTISITE_SUBDOMAIN_INSTALL = 1 ]; then
    echo "Converting to Multisite (subdomains)..."
    wp core multisite-convert --subdomains
  else
    echo "Converting to Multisite..."
    wp core multisite-convert
  fi
fi

# Ensure Multisite constants/vars are present
# This _looks_ redundant but is actually required because `wp-config.php`
# is regenerated whenever the container is re-created, and these constants will not be present.
if [ $WORDPRESS_MULTISITE = 1 ] && ! wp config has MULTISITE; then
  echo "Adding Multisite constants to 'wp-config.php'..."
  wp config set MULTISITE true --raw --type=constant
  if [ $WORDPRESS_MULTISITE_SUBDOMAIN_INSTALL = 1 ]; then
    wp config set SUBDOMAIN_INSTALL true --raw --type=constant
  else
    wp config set SUBDOMAIN_INSTALL false --raw --type=constant
  fi
  wp config set base / --type=variable
  wp config set DOMAIN_CURRENT_SITE '$host' --raw --type=constant
  wp config set PATH_CURRENT_SITE / --type=constant
  wp config set SITE_ID_CURRENT_SITE 1 --raw --type=constant
  wp config set BLOG_ID_CURRENT_SITE 1 --raw --type=constant
fi
