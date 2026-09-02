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

    public function getNewsById(int $id): array
    {
        $sql = "SELECT * FROM news WHERE id = ?";
        $result = $this->doSelect($sql, [$id]);
        return $result[0] ?? [];
    }

   
}
?>