#!/bin/bash

DIR=$(dirname $(pwd))

if [ ! `ps aux|grep CrontabForWaitPrepayOrder|grep -v grep` ]; then
    /usr/local/bin/php $DIR/webroot/route.php $DIR/script/CrontabForWaitPrepayOrder.php 
fi
