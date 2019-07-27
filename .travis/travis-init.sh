#!/usr/bin/env bash
set -e
set -o pipefail

if [[ "$TRAVIS_PHP_VERSION" != "hhvm" && "$TRAVIS_PHP_VERSION" != "hhvm-nightly" ]];
    then
        echo "PHP version: $TRAVIS_PHP_VERSION"
fi