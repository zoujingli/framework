@rmdir /s/q vendor thinkphp runtime
composer update --profile --prefer-dist --optimize-autoloader
composer dump-autoload --optimize