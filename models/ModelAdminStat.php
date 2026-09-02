<?php

class ModelAdminStat extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function order(string $startDate, string $endDate): array
    {
        // Validation stricte des dates pour éviter les injections SQL
        if (empty($startDate) || empty($endDate)) {
            return [
                'result' => [], 
                'order_paied' => 0, 
                'startDate' => 'Invalide', 
                'endDate' => 'Invalide'
            ];
        }

    
?>