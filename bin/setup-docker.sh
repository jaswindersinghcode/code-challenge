#!/bin/bash
cd /var/www/app

composer install

if [ -f .env ];then
	echo "Skip"
else
	cp .env.dist .env
fi
