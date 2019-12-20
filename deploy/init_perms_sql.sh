#!/bin/bash

ACTIONS=(
Company
CompanyMemo
Project
DealMemo
ActiveActiveDeal
Entity
EntityRel
DealDecision
MailSend
ItemPermission
FundEntity
LPControllerActual
LPFundLp
ComplianceMatter
ChecklistChecklist
LPEntityPermission
MailStrategy
MailList
KnowledgeCate
KnowledgeList
ChecklistKnowledgeChecklist
ContractTerm
ContractTermCheck
Admin
Member
Action
Permission
Group
AdminGroup
RolePermission
PermissionAction
Spy
DataStat
SystemLog
)

for action in ${ACTIONS[*]}
do
    action=$(echo $action | tr '[A-Z]' '[a-z]') 
    
    echo "insert into \`action\` (\`name\`,\`create_time\`) values('$action""_index',unix_timestamp()),('$action""_read',unix_timestamp()),('$action""_create',unix_timestamp()),('$action""_update',unix_timestamp()),('$action""_delete',unix_timestamp()),('$action""_search',unix_timestamp()),('$action""_select',unix_timestamp()),('$action""_select_search',unix_timestamp()),('$action""_clone',unix_timestamp()),('$action""_exporttocsv_index',unix_timestamp()),('$action""_autosave_update',unix_timestamp()),('$action""_autosave_create',unix_timestamp());"

    echo "insert into \`permission\` (\`name\`,\`create_time\`) values('$action""_read',unix_timestamp()),('$action""_write',unix_timestamp()),('$action""_export',unix_timestamp());"

    echo "insert into permission_action (permission_id, action_id, create_time) select p.id permission_id, a.id action_id, unix_timestamp() create_time from permission p, action a where p.name = '$action""_read' and a.name like '%$action""%' and (a.name = '$action""_index' or a.name = '$action""_read' or a.name = '$action""_search' or a.name = '$action""_select' or a.name = '$action""_select_search');"

    echo "insert into permission_action (permission_id, action_id, create_time) select p.id permission_id, a.id action_id, unix_timestamp() create_time from permission p, action a where p.name = '$action""_write' and a.name like '%$action""%' and (a.name = '$action""_create' or a.name = '$action""_update' or a.name = '$action""_delete' or a.name = '$action""_clone' or a.name = '$action""_autosave_create' or a.name = '$action""_autosave_update');"

    echo "insert into permission_action (permission_id, action_id, create_time) select p.id permission_id, a.id action_id, unix_timestamp() create_time from permission p, action a where p.name = '$action""_export' and a.name like '%$action""%' and (a.name = '$action""_exporttocsv_index');"
done
