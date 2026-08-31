<?php

class ModelIndex extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMainSliders()
    {
        $sql = "SELECT * FROM sliders";
        return $this->doSelect($sql);
    }

   
}
?>