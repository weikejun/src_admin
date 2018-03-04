#!/bin/bash

DIR=$(dirname $(pwd))

if [ ! `ps aux|grep CrontabForWaitPrepay_payorder|grep -v grep` ]; then
    /usr/local/bin/php $DIR/webroot/route.php $DIR/script/CrontabForWaitPrepay_payorder.php
fi
