# Acme Widget Co Basket System

This is a proof of concept for Acme Widget Co's new sales system. The basket system is designed to handle the addition of products, calculate the total cost of the basket, and apply delivery charges and special offers.

## Product Catalogue

The product catalogue contains three products:
- Red Widget (R01) - $32.95
- Green Widget (G01) - $24.95
- Blue Widget (B01) - $7.95

## Delivery Charges

Delivery charges are based on the total amount spent:
- Orders under $50 cost $4.95
- Orders under $90 cost $2.95
- Orders of $90 or more have free delivery

## Special Offers

The initial special offer implemented is "buy one red widget, get the second half price".

## Basket Interface

### Initialization

The basket is initialized with the product catalogue, delivery charge rules, and offers. The format of these inputs is up to you.

### Add Method

The `add` method takes the product code as a parameter and adds the corresponding product to the basket.

### Total Method

The `total` method returns the total cost of the basket, taking into account the delivery and offer rules.

## Example Usage

```php
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

$basket->add('B01');
$basket->add('G01');
echo $basket->total();  // Output: 37.85

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('R01');
echo $basket->total();  // Output: 54.38

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('R01');
$basket->add('G01');
echo $basket->total();  // Output: 60.85

$basket = new Basket($products, $deliveryCharges, $offers);
$basket->add('B01');
$basket->add('B01');
$basket->add('R01');
$basket->add('R01');
$basket->add('R01');
echo $basket->total();  // Output: 98.28

