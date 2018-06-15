<?php
Trait DBModel_Methods {
    public function getFieldList(){
        static $FIELD_LIST;
        if (!$FIELD_LIST) {
            $formClass = str_replace('Base_', 'Form_', __class__);
            $FIELD_LIST = [];
            foreach($formClass::getFieldsMap() as $field) {
                if ($field['type'] == 'seperator' 
                    || $field['type'] == 'rawText'
                    || $field['name'] == 'id') {
                    continue;
                }
                $FIELD_LIST[] = [
                    'name' => $field['name'],
                    'type' => 'string',
                    'default' => isset($field['default']) ? $field['default'] : null,
                    'key' => false,
                    'null' => isset($field['required']) ? !$field['required'] : false,
                ];
            }
            $FIELD_LIST[] = [
                'name' => 'id',
                'type' => 'string',
                'default' => null,
                'key' => true,
                'null' => false,
            ];
        }
        return $FIELD_LIST;
    }
}
