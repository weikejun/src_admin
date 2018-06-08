<?php
Trait DBModel_Methods {
    public function getFieldList(){
        static $FIELD_LIST;
        if (!$FIELD_LIST) {
            $formClass = str_replace('Base_', 'Form_', __class__);
            $FIELD_LIST = [];
            foreach($formClass::getFieldsMap() as $field) {
                if ($field['type'] == 'seperator' || $field['type'] == 'rawText') {
                    continue;
                }
                $FIELD_LIST[] = [
                    'name' => $field['name'],
                    'type' => 'string',
                    'default' => $field['default'],
                    'key' => ($field['name'] == 'id'),
                    'null' => !$field['required'],
                ];
            }
        }
        return $FIELD_LIST;
    }
}
