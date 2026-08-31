<?php

class ModelIndex extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMainSliders()
    {
        $sql = "SELECT * FROM sliders";
        return $this->doSelect($sql);
    }

    public function getSpecialOffers()
    {
        // Utilisation de requête préparée avec PDO (?)
        $sql = "SELECT * FROM products WHERE is_special_offer = ?";
        $result = $this->doSelect($sql, [1]);

        foreach ($result as $key => $row) {
            $priceCalculate = $this->calculateDiscount($row['price'], $row['discount_percent']);
            $result[$key]['price_total'] = $priceCalculate[1];
        }

        $firstRow = $result[0] ?? [];
        $timeSpecial = $firstRow['special_offer_expires_at'] ?? 0;

        $options = self::getoption(); 
        $durationSpecial = $options['special_time'] ?? 0;

        $timeEnd = $timeSpecial + $durationSpecial;
        
        date_default_timezone_set('Europe/Paris'); 
        $date = date('F d,Y H:i:s', $timeEnd);

        return [$result, $date];
    }

    public function getExclusiveProducts()
    {
        // Utilisation de requête préparée
        $sql = "SELECT * FROM products WHERE is_exclusive = ?";
        $result = $this->doSelect($sql, [1]);

        foreach ($result as $key => $row) {
            $priceCalculate = $this->calculateDiscount($row['price'], $row['discount_percent']);
            $result[$key]['price_total'] = $priceCalculate[1];
        }
        return $result;
    }

   
}
?>