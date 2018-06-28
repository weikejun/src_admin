<?php
class Page_Admin_TimeRangeFilter extends Page_Admin_IFilter{

    public function setFilter(DBModel $model){
        parse_str($_GET['__filter'],$params);
        $start_time=$params[$this->getParamName()."__start"];
        $end_time=$params[$this->getParamName()."__end"];
        $paramName = $this->getParamName();
        if($this->foreignTable && $this->inKey) {
            list($fKey, $key) = explode('|', $paramName);
            $finder = new $this->foreignTable;
            if($start_time){
                $finder->addWhere($fKey,strtotime($start_time),">=");
            }
            if($end_time){
                $finder->addWhere($fKey,strtotime($end_time),"<=");
            }
            $objs = $finder->setCols([$key])->findMap($key);
            $model->addWhere($this->inKey, array_keys($objs), 'IN');
        } else {
            if($start_time){
                $model->addWhere($this->getParamName(),strtotime($start_time),">=");
            }
            if($end_time){
                $model->addWhere($this->getParamName(),strtotime($end_time),"<=");
            }
        }
    }

    public function toHtml(){
        $html='';
        $params = [];
        $dateClass = $this->dateClass ? $this->dateClass : 'datepicker';
        if (isset($_GET['__filter'])) {
            parse_str($_GET['__filter'],$params);
        }
        $startTm=isset($params[$this->getParamName().'__start']) ? $params[$this->getParamName().'__start'] : '';
        $endTm=isset($params[$this->getParamName().'__end']) ? $params[$this->getParamName().'__end'] : '';
        if ($startTm || $endTm) {
            $this->class .= ' keep-all';
        }
        $html.='<ul style="margin:0;" class="'.$this->class.' nav nav-pills filter">'.
            '<li class="filter-text">'.htmlspecialchars($this->getName()).'</li>'.
            '<li><label class="radio-inline"><input class="'.$dateClass.'" value="'.htmlspecialchars($startTm).'" type="text" name="'.$this->getParamName().'__start" autocomplete=off></label></li>'."\n".
            '<li>&nbsp;-&nbsp;<label class="radio-inline"><input class="'.$dateClass.'" value="'.htmlspecialchars($endTm).'" type="text" name="'.$this->getParamName().'__end" autocomplete=off></label></li>'."\n".
            '';//'<li>示例：'.date("Y-m-d H:i:s").'</li>'."\n";
        $html.='</ul>';

        return $html;
    }

}


