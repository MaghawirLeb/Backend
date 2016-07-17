<?php

class Account
{
    public $Id;
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
        if (!empty($params)) {
            foreach ($this as $key => $value) {
                if (isset($params[$key])) {
                    $this->$key = $params[$key];
                }
            }
        }
    }
}