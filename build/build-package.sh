#!/bin/sh

composer run clean

mkdir -p ./tmp/tawkmagento2
cp -r ./view ./tmp/tawkmagento2
cp -r ./etc ./tmp/tawkmagento2
cp -r ./Setup ./tmp/tawkmagento2
cp -r ./Model ./tmp/tawkmagento2
cp -r ./Controller ./tmp/tawkmagento2
cp -r ./Block ./tmp/tawkmagento2
cp -r ./Helper ./tmp/tawkmagento2
cp -r ./Api ./tmp/tawkmagento2
cp ./registration.php ./tmp/tawkmagento2
cp ./composer.json ./tmp/tawkmagento2
cp README.md ./tmp/tawkmagento2

(cd ./tmp && zip -9 -rq ./tawkmagento2.zip ./tawkmagento2)
