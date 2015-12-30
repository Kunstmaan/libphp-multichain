#!/bin/bash

if [ -z $1 ]; then
    bin/phpunit --bootstrap vendor/autoload.php tests/ --verbose --colors=always --debug
else
    bin/phpunit --bootstrap vendor/autoload.php tests/ --verbose --colors=always --group=$1 --debug
fi