<?php

require("controllers/AbsBaseController.php");

class BusinessTypeController extends BaseController
{
    public function GetTableName()
    {
        return "BusinessType";
    }

    public function GetKey()
    {
        return "BusinessTypeId";
    }
}