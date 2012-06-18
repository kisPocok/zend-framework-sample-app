<?php

class Application_Model_Bookmark extends Zend_Db_Table_Abstract
{
    /**
     * Table name
     */
    protected $_name = 'bookmarks';

    /**
     * Primary field
     */
    protected $_primary = 'id';

    /**
     * Table fields
     */
    protected $title;
    protected $url;
    protected $id;

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid property');
        }
        return $this->$method();
    }

    public function setTitle($title)
    {
        $title = strtolower(trim($title));
        $this->title = ucfirst($title);
        return $this;
    }
 
    public function getTitle()
    {
        return $this->title;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
 
    public function getUrl()
    {
        return $this->url;
    }

    public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}