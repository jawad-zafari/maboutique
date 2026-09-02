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

    public function saveSetting(array $data): void
    {
        if (empty($data)) return;

        foreach ($data as $settingKey => $value) {
            
            // 
            if ($settingKey === 'csrf_token') {
                continue;
            }

            // exécuter la requête préparée.
            $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
            $this->doQuery($sql, [$value, $settingKey]);
        }
    }
}
?>