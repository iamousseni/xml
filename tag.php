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
        $string = '';
        $string = '<';
        foreach($this->getChilds() as $x){
           $string.= $x->generateXML();
        }
        
        return $string;
    }
}