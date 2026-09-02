<?php

class ModelAdminSetting extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSettings(): array
    {
        $sql = "SELECT * FROM settings";
        $result = $this->doSelect($sql);
        
        $settings = [];
        if (is_array($result)) {
            foreach ($result as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
        
        return $settings;
    }

    
    
}
?>