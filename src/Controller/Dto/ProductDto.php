<?php

namespace App\Controller\Dto;

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
}
