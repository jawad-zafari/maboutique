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

    // Le modèle reçoit des données 100% propres et prêtes à être insérées
    public function saveSetting(array $cleanData): void
    {
        if (empty($cleanData)) return;

        foreach ($cleanData as $settingKey => $value) {
            $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
            $this->doQuery($sql, [$value, $settingKey]);
        }
    }
}
?>