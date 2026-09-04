<?php

class ModelIndex extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMainSliders(): array
    {
        $sql = "SELECT * FROM sliders";
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function getSpecialOffers(): array
    {
        // La variable est fortement typée et ne provient pas d'une source externe
        $sql = "SELECT * FROM products WHERE is_special_offer = ?";
        $result = $this->doSelect($sql, [1]);
        return is_array($result) ? $result : [];
    }

    public function getExclusiveProducts(): array
    {
        $sql = "SELECT * FROM products WHERE is_exclusive = ?";
        $result = $this->doSelect($sql, [1]);
        return is_array($result) ? $result : [];
    }

    public function getMostViewedProducts(int $limit): array
    {
        // La variable $limit est fortement typée et ne provient pas d'une source externe
        $sql = "SELECT * FROM products ORDER BY views DESC LIMIT " . $limit;
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function getLatestProducts(int $limit): array
    {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT " . $limit;
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }
    public function getLatestNews(int $limit = 3): array
    {
        $sql = "SELECT * FROM news ORDER BY id DESC LIMIT " . $limit;
        $result = $this->doSelect($sql);
        return is_array($result) ? $result : [];
    }

    public function getBrands(int $limit = 6): array
    {
        $sql = "SELECT * FROM categories WHERE is_brand = ? ORDER BY id DESC LIMIT " . $limit;
        $result = $this->doSelect($sql, [1]);
        return is_array($result) ? $result : [];
    }

    public function getTvSettings(): array
    {
        $sql = "SELECT * FROM settings WHERE setting_key IN ('tv_video_link', 'tv_cover_image')";
        $results = $this->doSelect($sql);
        
        $settings = [];
        if (is_array($results)) {
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $settings;
    }
}
?>