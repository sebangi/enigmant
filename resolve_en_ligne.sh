#!/bin/bash

cd public

for fich in $(find images/ -name '*') do 
do
        if [ -f $fich ]
        then
		 /usr/bin/php7.3-cli ../bin/console liip:imagine:cache:resolve  $fich
        fi
done
