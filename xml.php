<?php

/*
    @author Ousseni Bara
    @github iamousseni
    version: 1.0.0
    last modified: 31/03/2018
*/

class Tag
{
  /* Properties */
  private $name;
  private $innerText;
  private $attributes;
  private $parent;
  private $childs = [];

  /* Methods */
  public function __construct(
    string $name,
    string $innerText = null,
    array $attribute = null,
    $parent = null,
    $childs = null
  ) {
    $this->setName($name);
    $this->setInnerText($innerText);
    if ($attribute !== null)
      $this->setAttribute($attribute);
    $this->setParent($parent);
    if ($childs !== null)
      $this->setChild($childs);
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

  public function setAttribute(array $attribute)
  {
    foreach ($attribute as $key => $value) {
      $this->attributes[$key] = $value;
    }
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
    $this->childs[] = $child;
  }

  public function getChilds()
  {
    return $this->childs;
  }

  public function generateXML_from_tag()
  {
    $xmlString = "";
    $xmlString .= "<" . $this->getName();
    if (null !== $this->getAttributes()) {
      foreach ($this->getAttributes() as $key => $value) {
        $xmlString .= " $key = \"" . $value . "\"";
      }
    }

    if (null == $this->getInnerText() && null == $this->getChilds()) {
      $xmlString .= " />";
    } else {
      $xmlString .= ">";
      $xmlString .= $this->getInnerText();
      if ($this->getChilds() !== null) {
        foreach ($this->getChilds() as $child) {
          $xmlString .= $child->generateXML_from_tag();
        }
      }
      $xmlString .= "</" . $this->getName() . ">";
    }
    return $xmlString;
  }
}

class XML extends Tag
{
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

  public function setRoot(
    string $name,
    string $innerText = null,
    array $attribute = []
  ) {
    $this->root = new Tag($name, $innerText, $attribute);
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
    $child = null
  ) {
    //if root was setted
    if ($this->getRoot() != null) {
      //if parent has been specified
      if ($parent != null) {
        //create Tag obj
        $tag = new Tag($name, $innerText, $attribute, $parent, $child);
        //set this tag as a child of parent that was specified
        $parent->setChild($tag);
        return $tag;
      } else {
        $tag = new Tag($name, $innerText, $attribute, $this->getRoot(), $child);
        $this
          ->getRoot()
          ->setChild($tag);

        return $tag;
      }
    } else {
      return false;
    }
  }

  public function getXML_string()
  {
    if ($this->getRoot() === null)
      return false;
    else
      return $this
        ->getRoot()
        ->generateXML_from_tag();
  }

  private function generateXML($tagName, $tagValue)
  {
    $xmlString = '<' . $tagName;
    if ($tagValue === null) {
      $xmlString .= '/>';
    } else {
      $xmlString .= '>';
      if (!is_array($tagValue)) {
        $xmlString .= $tagValue . '</' . $tagName . '>';
      } else {
        foreach ($tagValue as $key => $childValue) {
          $xmlString .= $this->generateXML($key, $childValue);
        }
        $xmlString .= '</' . $tagName . '>';
      }
    }
    return $xmlString;
  }

  public function getXML_string_from_array(array $arrayData)
  {
    $xmlString = '';
    foreach ($arrayData as $key => $value) {
      $xmlString .= $this->generateXML($key, $value);
    }
    return $xmlString;
  }

  private function addTagOnXML(array $data)
  {
    $xmlArray = [];
    foreach ($data as $child) {
      if ($child->getChilds() != null) {
        $xmlArray[$child->getName()] = $this->addTagOnXML($child->getChilds());
      } else {
        $xmlArray[$child->getName()] = $child->getInnerText();
      }
    }
    return $xmlArray;
  }

  public function getArray_from_XML()
  {
    if ($this->getRoot() !== null) {
      $xmlArray[$this->getRoot()->getName()] = $this->getRoot()->getInnerText();
      if ($this->getRoot()->getChilds() !== null) {
        $xmlArray[$this->getRoot()->getName()] = $this->addTagOnXML($this->getRoot()->getChilds());
      }
      return $xmlArray;
    } else {
      return false;
    }
  }

  public function getArray_from_XML_from_XML_OBJ($XML){
    if ($XML->getRoot() !== null) {
      $xmlArray[$XML->getRoot()->getName()] = $XML->getRoot()->getInnerText();
      if ($XML->getRoot()->getChilds() !== null) {
        $xmlArray[$XML->getRoot()->getName()] = $XML->addTagOnXML($XML->getRoot()->getChilds());
      }
      return $xmlArray;
    } else {
      return false;
    }
  }

  public function createXML_file_from_array(array $arrayData, string $fileName = 'xmlFile')
  {
    $xmlFile = fopen($fileName.'.xml', 'w');
    $status = fwrite($xmlFile, $this->getXML_string_from_array($arrayData));
    fclose($xmlFile);
    echo $status !== false ? 'File created successfull!' : 'Error! There was an array on writing into file.';
    return $status !== false ? true : false;
  }

  public function createXML_file_from_XMLstring(string $XMLstring, string $fileName = 'xmlFile')
  {
    $xmlFile = fopen($fileName.'.xml', 'w');
    $status = fwrite($xmlFile, $XMLstring);
    fclose($xmlFile);
    echo $status !== false ? 'File created successfull!' : 'Error! There was an array on writing into file.';
    return $status !== false ? true : false;
  }

  public function createXML_file_from_XML_OBJ($fileName = 'xmlFile')
  {
    $xmlString = $this->getXML_string();
    if ($xmlString !== false)
      return  $this->createXML_file_from_XMLstring($xmlString, $fileName);
    else
      return false;
  }
}

