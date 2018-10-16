#!/bin/bash

for f in $(git di bfcf203baae38ac29e2ec1fb0ecb0764bcd744dd --summary|grep controller|awk '{print $4}')
do 
    RES=$(echo $(basename $f Controller.class.php)|tr '[A-Z]' '[a-z]')
    echo $RES
    #TS=$(date +%s)
    #echo "insert into \`action\` (\`name\`,\`description\`,\`update_time\`,\`create_time\`) values('$RES""_index','：首页',$TS,$TS),('$RES""_read','：查看',$TS,$TS),('$RES""_create','：新增',$TS,$TS),('$RES""_update','：更新',$TS,$TS),('$RES""_delete','：删除',$TS,$TS),('$RES""_search','：搜索',$TS,$TS),('$RES""_select','：选择页',$TS,$TS),('$RES""_select_search','：选择页搜索',$TS,$TS) on duplicate key update \`name\`=\`name\`;"
done
