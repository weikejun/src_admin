#!/bin/bash

cd $(cd $(dirname $0);pwd)

let last=$(date +%s)-30*86400

DATE_STR=$(date -d "@$last" +%Y%m)
BUYER_LOG=buyerapi_"$DATE_STR"*.log
PAY_LOG=pay_notify_"$DATE_STR"*.log
ACTION_LOG="$DATE_STR"*.log

tar czf buyerapi_"$DATE_STR".tgz $BUYER_LOG
[ $? == '0' ] && rm -fr $BUYER_LOG 

tar czf pay_notify_"$DATE_STR".tgz $PAY_LOG
[ $? == '0' ] && rm -fr $PAY_LOG 

tar czf "$DATE_STR".tgz $ACTION_LOG
[ $? == '0' ] && rm -fr $ACTION_LOG
