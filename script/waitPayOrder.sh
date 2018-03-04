#!/bin/bash

DIR=$(dirname $(pwd))

if [ ! `ps aux|grep CrontabForWaitPayOrder|grep -v grep` ]; then
    /usr/local/bin/php $DIR/webroot/route.php $DIR/script/CrontabForWaitPayOrder.php 
fi
