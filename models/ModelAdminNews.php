<?php

class ModelAdminNews extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getNews(): array
    {
        $sql = "SELECT * FROM news ORDER BY id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    
}
?>