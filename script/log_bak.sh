#!/bin/bash

cd $(cd $(dirname $0);pwd)

let last=$(date +%s)-30*86400

DATE_STR=$(date -d "@$last" +%Y%m)
ACTION_LOG="$DATE_STR"*.log

tar czf "$DATE_STR".tgz $ACTION_LOG
[ $? == '0' ] && rm -fr $ACTION_LOG
