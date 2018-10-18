#!/bin/bash

set -e

HOST=HOST_GOES_HERE
USER=USER_GOES_HERE
DESTINATION=remote/path/to/theme
PLUGINDESTINATION=remote/path/to/plugins
BASEDIR=$(dirname "$0")
DEPLOYIGNORE=$BASEDIR/.deployignore

if [ -z $HOST ] || [ -z $USER ]; then
  echo "Please pass the SFTP host and user name for your site."
  exit 1
fi

echo "Deploying WordPress theme..."
rsync \
  -rlvz \
  --exclude-from="$DEPLOYIGNORE" \
  --ipv4 \
  --delete-after \
  -e 'ssh -p 2222 -o StrictHostKeyChecking=no' \
  --temp-dir=~/tmp/ \
  $TRAVIS_BUILD_DIR/* \
  $USER@$HOST:$DESTINATION

echo "Deploying WordPress plugins..."
rsync \
  -rlvz \
  --ipv4 \
  --exclude=".gitignore" \
  -e 'ssh -p 2222 -o StrictHostKeyChecking=no' \
  --temp-dir=~/tmp/ \
  $TRAVIS_BUILD_DIR/plugins \
  $USER@$HOST:$PLUGINDESTINATION
