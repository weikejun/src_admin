<?php
class Page_Admin_ChoiceFilter extends Page_Admin_IFilter{

    public function setFilter(DBModel $model){
        parse_str($_GET['__filter'],$params);
        $reqVal=$params[$this->getParamName()];
        foreach($this->choices as $i => $choice) {
            if ($reqVal === $choice[0]) {
                if (isset($choice[2])) {
                    $model->addWhereRaw($choice[2]);
                } else {
                    $model->addWhere($this->getParamName(),$reqVal);
                }
            }
        }
    }

    public function toHtml(){
        $html='';
        parse_str($_GET['__filter'],$params);
        $reqVal=$params[$this->getParamName()];
        if ($reqVal) {
            $this->class .= ' keep-all';
        }
        $html.='<ul style="margin:0;" class="'.$this->class.' nav nav-pills filter">'.
            '<li class="filter-text">'.htmlspecialchars($this->getName()).'</li>'.
            '<li '.($reqVal?"":'class="active"').'><label class="radio-inline"><input '.($reqVal?"":'checked="checked"').' type="radio" name="'.$this->getParamName().'" value="">全部</label></li>'."\n";
        
        foreach($this->choices as $choice){
            $html.="<li><label><input type='radio' name='".$this->getParamName()."' ".
                ($choice[0]===$reqVal?"checked=checked":"").
                " value={$choice[0]}>{$choice[1]}</label></li>\n";
        }
        $html.='</ul>';
        return $html;
    }

}

