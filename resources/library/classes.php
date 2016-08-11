<?php

class Entity
{
    protected function _init($class_model, $params)
    {
        if (!empty($params)) {
            foreach ($class_model as $key => $value) {
                if (isset($params[$key])) {
                    $this->$key = $params[$key];
                }
            }
        }
    }
}

class Account extends Entity
{
    public $AccountId;
    public $Name;
    public $BusinessTypeId;
    public $Description;
    public $Logo;
    public $Address;
    public $RegionId;
    public $Longitude;
    public $Latitude;

    function __construct($params = null)
    {
        $this->_init($this, $params);
    }
}

class Region extends Entity
{
    public $RegionId;
    public $Name;

    function __construct($params = null)
    {
        $this->_init($this, $params);
    }
}

class BusinessType extends Entity
{
    public $BusinessTypeId;
    public $Name;

    function __construct($params = null)
    {
        $this->_init($this, $params);
    }
}