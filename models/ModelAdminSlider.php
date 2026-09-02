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

    public function getSliderById(int $id): array
    {
        $sql = "SELECT * FROM sliders WHERE id = ?";
        $result = $this->doSelect($sql, [$id], 'fetch');
        return is_array($result) ? $result : [];
    }

    
}
?>