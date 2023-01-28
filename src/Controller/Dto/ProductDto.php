<?php

namespace App\Controller\Dto;

use App\Entity\Product;

class ProductDto
{
    public int $id;
    public string $name;
    public $price;

    public function __construct(int $id,string $name, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    //@todo Вынести?
    public static function productMapping(Product $product): ProductDto
    {
        return new ProductDto($product->getId(), $product->getName(), $product->getPrice());
    }
}

//@todo fix it
class CartProductDto
{
    public int $id;
    public string $name;
    public float $price;
    public int $quantity;

    public function __construct(int $id,string $name, float $price, int $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    //@todo Вынести?
    public static function productMapping(Product $product, int $quantity): CartProductDto
    {
        return new CartProductDto($product->getId(), $product->getName(), $product->getPrice(), $quantity);
    }
}
