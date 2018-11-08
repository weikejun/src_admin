<?php
class Page_Admin_RangeFilter extends Page_Admin_IFilter{

    public function setFilter(DBModel $model){
        parse_str($_GET['__filter'],$params);
        $start=$params[$this->getParamName()."__start"];
        $end=$params[$this->getParamName()."__end"];
        if($start){
            $model->addWhere($this->getParamName(),$start,">=");
        }
        if($end){
            $model->addWhere($this->getParamName(),$end,"<=");
        }
    }

    public function toHtml(){
        $html='';
        parse_str($_GET['__filter'],$params);
        $reqVal=$params[$this->getParamName()];
        $start=isset($params[$this->getParamName().'__start']) ? $params[$this->getParamName().'__start'] : '';
        $end=isset($params[$this->getParamName().'__end']) ? $params[$this->getParamName().'__end'] : '';
        if ($start || $end) {
            $this->class .= ' keep-all';
        }
        $html.='<ul style="margin:0;" class="'.$this->class.' nav nav-pills filter">'.
            '<li class="filter-text">'.htmlspecialchars($this->getName()).'</li>'.
            '<li><label class="radio-inline"><input class="" value="'.htmlspecialchars($start).'" type="text" name="'.$this->getParamName().'__start"></label></li>'."\n".
            '<li>&nbsp;-&nbsp;<label class="radio-inline"><input class="" value="'.htmlspecialchars($end).'" type="text" name="'.$this->getParamName().'__end"></label></li>'."\n";
        $html.='</ul>';

        return $html;
    }

}


