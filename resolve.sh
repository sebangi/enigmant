#!/bin/bash

cd public

for fich in $(find images/ -name '*') do 
do
        if [ -f $fich ]
        then
		php ../bin/console liip:imagine:cache:resolve  $fich
        fi
done
