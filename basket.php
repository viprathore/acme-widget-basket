<?php

class Basket {
    private $products = [];
    private $deliveryCharges = [];
    private $offers = [];
    private $basket = [];

    public function __construct($products, $deliveryCharges, $offers) {
        $this->products = $products;
        $this->deliveryCharges = $deliveryCharges;
        $this->offers = $offers;
    }

    public function add($productCode) {
        if (isset($this->products[$productCode])) {
            $this->basket[] = $productCode;
        } else {
            throw new Exception("Product code $productCode does not exist in the product catalogue.");
        }
    }

    public function total() {
        $total = 0.0;
        $productCounts = array_count_values($this->basket);
       
        // Apply offers
        foreach ($this->offers as $offer) {
            $total += $offer($productCounts, $this->products);
        }
       
        // Calculate total without offers
        foreach ($productCounts as $code => $count) {
            if (!isset($this->offers[$code])) {
                $total += $this->products[$code]['price'] * $count;
            }
        }
      
        // Apply delivery charges
        $deliveryCharge = 0.0;
        if ($total < 50) {
            $deliveryCharge = $this->deliveryCharges['under_50'];
        } elseif ($total < 90) {
            $deliveryCharge = $this->deliveryCharges['under_90'];
        }
        $total += $deliveryCharge;

        return number_format($total, 2, '.', '');
    }
}

function redWidgetOffer(&$productCounts, $products) {
    $total = 0.0;
    if (isset($productCounts['R01'])) {
        if($productCounts['R01'] > 1) {
            $halfPriceCount = 1;
            $fullPriceCount = $productCounts['R01'] - $halfPriceCount;
            $total += $products['R01']['price'] * $fullPriceCount;
            $total += ($products['R01']['price'] / 2) * $halfPriceCount;
            unset($productCounts['R01']);
        } else if($productCounts['R01'] == 1) {
            $total += $products['R01']['price'];
            unset($productCounts['R01']);
        }       
    }
    return $total;
}

$products = [
    'R01' => ['name' => 'Red Widget', 'price' => 32.95],
    'G01' => ['name' => 'Green Widget', 'price' => 24.95],
    'B01' => ['name' => 'Blue Widget', 'price' => 7.95],
];

$deliveryCharges = [
    'under_50' => 4.95,
    'under_90' => 2.95,
    'over_90' => 0.0
];

$offers = [
    'R01' => 'redWidgetOffer'
];

$basket = new Basket($products, $deliveryCharges, $offers);

// Example usage
$basket->add('B01');
$basket->add('G01');
echo "Total => " . $basket->total() . " (B01 + G01) <br>";  

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('R01');
echo "Total => " . $basket->total() . " (R01 + R01) <br>";  

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('G01');
echo "Total => " . $basket->total() . " (R01 + G01) <br>";  

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('B01');
$basket->add('B01');
$basket->add('R01');
$basket->add('R01');
$basket->add('R01');
echo "Total => " . $basket->total() . ' (B01 + B01 + R01 + R01 + R01) <br>';  
