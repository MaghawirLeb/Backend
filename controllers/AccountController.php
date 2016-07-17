<?php

require("controllers/AbsBaseController.php");

class AccountController extends BaseController
{
    public function GetTableName() : string
    {
        return "Account";
    }

    public function GetKey() : string
    {
        return "AccountId";
    }
}