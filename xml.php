<?php
class XML extends Tag{
    /* Properties */
    private $version;
    private $encoding;
    private $root;

    /* Methods */
    public function __construct(string $version = '1.0', string $encoding = 'utf-8')
    {
        $this->setVersion($version);
        $this->setEncoding($encoding);
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setRoot(string $name,
        string $innerText = '',
        array $attribute = [],
        $parent = null,
        $child = null)
    {
        $this->root = $this->createTag($name, $innerText, $attribute, $parent, $child);
    }

    public function getRoot()
    {
        return $this->root;
    }

    public function createTag(
        string $name,
        string $innerText = '',
        array $attribute = [],
        $parent = null,
        $child = null){
            //if root was setted
            if($this->getRoot() != null){
                //if parent has been specified
                if($parent != null){
                    //create Tag obj
                    $tag = new Tag($name, $innerText, $attribute, $parent, $child);
                    //set this tag as a child of parent that was specified
                    $parent->setChild($tag);
                    return $tag;
                }else{
                    $tag = new Tag($name, $innerText, $attribute, $this->getRoot(), $child);
                    $this
                        ->getRoot()
                        ->setChild($tag);

                    return $tag;
                }
            }else{
                return false;
            }
    }

    public function getXML_string(){
        
    }



}
