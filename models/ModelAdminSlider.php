<?php

class ModelAdminSlider extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getslider(): array
    {
        $sql = "SELECT * FROM sliders ORDER BY id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

   
}
?>