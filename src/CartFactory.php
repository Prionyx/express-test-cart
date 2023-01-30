<?php

namespace App;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;

//@todo куда убрать?
class CartFactory
{
    public function create(): Cart
    {
        return new Cart();
    }

    public function createItem(Product $product): CartItem
    {
        return (new CartItem())->setProduct($product)->setQuantity(1);
    }
}
