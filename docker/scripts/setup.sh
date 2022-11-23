#!/bin/bash

set -e

# Ensure MySQL connection is up before proceeding.
until mysql -uwordpress -pwordpress -hdb wordpress; do
  >&2 echo "Waiting for MySQL ..."
  sleep 1
done

# Configure WordPress
if [ ! -f wp-config.php ]; then
	echo "Configuring WordPress..."
	wp config create --dbname=$WORDPRESS_DB_NAME --dbuser=$WORDPRESS_DB_USER --dbpass=$WORDPRESS_DB_PASSWORD --dbhost=$WORDPRESS_DB_HOST --extra-php <<PHP
// This allows the WordPress site to run securly behind the ups-dock reverse proxy
if (strpos(\$_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	\$_SERVER['HTTPS'] = 'on';
}

/* Set home / site URL based on incoming request for local development to avoid SSL headaches */
\$schema = \$_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
\$host = parse_url('$WORDPRESS_URL', PHP_URL_HOST);
\$port = parse_url('$WORDPRESS_URL', PHP_URL_PORT);
\$path = parse_url('$WORDPRESS_URL', PHP_URL_PATH);
\$port = \$port ? ":$port" : '';
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
if ! $(wp core is-installed); then
	wp core install \
		--url="$WORDPRESS_URL" \
		--title="$WORDPRESS_TITLE" \
		--admin_user="$WORDPRESS_ADMIN_USER" \
		--admin_email="$WORDPRESS_ADMIN_EMAIL" \
		--admin_password="$WORDPRESS_ADMIN_PASSWORD"

	# Activate this theme.
	wp theme activate "$WORDPRESS_THEME_NAME"

	# Ensure correct permissions for files / directories creating during install.
	if [ -z "$SKIP_CHOWN" ]; then
		chown -Rf nginx.nginx /var/www/html
	fi
fi
