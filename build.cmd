@echo off
title Composer Update and Optimize
composer update --profile --prefer-dist --optimize-autoloader
composer dump-autoload --optimize
exit