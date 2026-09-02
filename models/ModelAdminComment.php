<?php

class ModelAdminComment extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getComment(): array
    {
        $sql = "SELECT * FROM comments ORDER BY id DESC";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    
}
?>