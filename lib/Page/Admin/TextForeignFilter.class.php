<?php
class Page_Admin_TextForeignFilter extends Page_Admin_IFilter{


    public function setFilter(DBModel $model){
        parse_str($_GET['__filter'],$params);
        $paramName=$this->getParamName();
        $forSelField = $this->forSelField ? $this->forSelField : 'id';
        if(isset($params[$paramName])&&strlen($params[$paramName])!=0){
            $val = $params[$paramName];
            list($paramName, $foreignKey) = explode('|', $paramName);
            $fFinder = new $this->foreignTable;
            $objs = $fFinder->addWhere($paramName, ($this->fusion ? "%$val%" : $val), ($this->fusion ? 'like' : '='))->setCols([$forSelField])->findMap($forSelField);
            $model->addWhere($foreignKey, array_keys($objs), 'IN');
        }
    }

    public function toHtml(){
        
        $html='';
        parse_str($_GET['__filter'],$params);
        
        $paramNames=$this->getParamName();
        if(!is_array($paramNames)){
            $paramNames=[$paramNames];
        }
        $Names=$this->getName();
        if(!is_array($Names)){
            $Names=[$Names];
        }
        foreach($paramNames as $i=>$paramName){
                /*
                    $html.="<input type='hidden' name='".$paramName."' ".
                    " value={$params[$paramName]}>";
                 */
            $paramVal = (isset($params[$paramName]) ? $params[$paramName] : '');
            if ($paramVal !== '') {
                $this->class .= ' keep-all';
            }
            $html.='<ul style="margin:0;" class="'.$this->class.' nav nav-pills filter">'.
                '<li class="filter-text">'.htmlspecialchars($Names[$i]).'</li>'.
                '<li><label class="radio-inline"><input value="'.htmlspecialchars($params[$paramName]).'" type="text" name="'.$paramName.'"></label></li>'."\n";
            $html.='</ul>';
        }
        return $html;
    }

}


