<?php
class Tag
{
    /* Properties */
    private $name;
    private $innerText;
    private $attributes = [];
    private $parent;
    private $childs = [];

    /* Methods */
    public function __construct(
        string $name,
        string $innerText = '',
        array $attribute = [],
        $parent = null,
        $childs = null
    ) {
        $this->setName($name);
        $this->setInnerText($innerText);
        $this->setAttribute($attribute);
        $this->setParent($parent);
        $this->setChilds($childs);
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setInnerText($innerText)
    {
        $this->innerText = $innerText;
    }

    public function getInnerText()
    {
        return $this->innerText;
    }

    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setChild($child)
    {
        $this->childs = $child;
    }

    public function getChilds()
    {
        return $this->childs;
    }

    public function generateXML(){
		$ret = "";
		$ret .= "<".$this->tag;
		if(isset($this->attributi)){
			foreach($this->attributi as $k => $v){
				$ret .= " ".$k.'="'.$v.'"';
			}
		}
		if(!isset($this->text) && !isset($this->figli)){
			$ret .= " />";
		}else{
			$ret .= ">";
			if(isset($this->text)){
				$ret .= $this->text;
			}elseif(isset($this->figli) && is_array($this->figli)){
				foreach($this->figli as $f){
					$ret .= $f->generateXML();
				}
			}				
			$ret .= "</".$this->tag.">";	
		}
		return $ret;
	}
}
