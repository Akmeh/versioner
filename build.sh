#!/bin/bash

echo "Composer"
composer install --prefer-dist -n -q

git describe --abbrev=0 --tags > .version

echo "Versioning"
php src/version version:inc patch

export VERSION=`cat .version`

echo "Version to be deployed $VERSION"

export TODAY=`date +%Y-%m-%d:%H:%M:%S`
git config --global user.email "release@kraken.com"
git config --global user.name "kraken"

echo "Taging"
git release $VERSION
