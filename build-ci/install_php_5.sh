#!/usr/bin/env bash
#
#  This source file is subject to the MIT License that is bundled
#  with this package in the MIT license.

CURRENT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
TRAVIS_BUILD_DIR="${TRAVIS_BUILD_DIR:-$(dirname $(dirname $CURRENT_DIR))}"

install_eio() {
	git clone -q https://github.com/rosmanov/pecl-eio -b master /tmp/eio
	cd /tmp/eio

	phpize &> /dev/null
	./configure &> /dev/null

	make --silent -j4 &> /dev/null
	make --silent install

	if [ -z $(php -m | grep eio) ]; then
        phpenv config-add "${TRAVIS_BUILD_DIR}/build-ci/ini/eio.ini"
    fi
}