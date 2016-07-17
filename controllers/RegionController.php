<?php

require("controllers/AbsBaseController.php");

class RegionController extends BaseController
{
    public function GetTableName() : string
    {
        return "Region";
    }

    public function GetKey() : string
    {
        return "RegionId";
    }
}