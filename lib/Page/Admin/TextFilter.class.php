<?php
class Page_Admin_TextFilter extends Page_Admin_IFilter{

    public function setFilter(DBModel $model){
        parse_str($_GET['__filter'],$params);
        $paramNames=$this->getParamName();
        if(!is_array($paramNames)){
            $paramNames=[$paramNames];
        }

        foreach($paramNames as $paramName){
            if(isset($params[$paramName])&&strlen($params[$paramName])!=0){
                if($this->fusion){
                    $model->addWhere($paramName,"%{$params[$paramName]}%",'like');
                }else{
                    if($this->in) {
                        $model->addWhere($paramName,explode(',',$params[$paramName]),'IN');
                    } else {
                        $model->addWhere($paramName,$params[$paramName]);
                    }
                }
            }
        }
    }

    public function toHtml(){
        
        $html='';
        $params = [];
        if (isset($_GET['__filter'])) {
            parse_str($_GET['__filter'],$params);
        }
        
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
            $html.='<ul style="margin:0;'.($this->hidden?'display:none;':'').'" class="'.$this->class.' nav nav-pills filter">'.
                '<li class="filter-text">'.htmlspecialchars($Names[$i]).'</li>'.
                '<li><label class="radio-inline"><input value="'.htmlspecialchars($paramVal).'" type="text" name="'.$paramName.'"></label></li>'."\n";
            $html.='</ul>';
        }
        return $html;
    }

}


