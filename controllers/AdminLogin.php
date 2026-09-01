<?php

class AdminLogin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Model::sessionInit();
    }

   
}
?>