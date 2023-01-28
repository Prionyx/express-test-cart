<?php

namespace App;

use App\Controller\Dto\ProductDto;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class CartManager
{
    private CartRepository $cartRepository;
    private EntityManagerInterface $entityManager;
    private CartSessionStorage $cartSessionStorage;

    public function __construct(CartRepository $cartRepository, EntityManagerInterface $entityManager, CartSessionStorage $cartSessionStorage)
    {
        $this->cartRepository = $cartRepository;
        $this->entityManager = $entityManager;
        $this->cartSessionStorage = $cartSessionStorage;
    }

    public function getCurrentCart(): Cart
    {
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart) {
            //@todo  Добавить фабрику?
            $cart = new Cart();
        }

        return $cart;
    }

    #[ArrayShape([
        'productsCount' => "int",
        'productsPrice' => "float",
        'productsList' => "\App\Controller\Dto\ProductDto[]|array"
    ])] public function getCartData(): array
    {
        $cart = $this->getCurrentCart();

        $productCount = 0;
        $productPrice = 0;
        /** @var CartItem $item */
        foreach ($cart->getItems() as $item) {
            $productCount += $item->getQuantity();
            //@todo неверно
            $productPrice += (float)$item->getProduct()?->getPrice() * $productCount; //@todo Работа с ценами
        }
        $productsDto = array_map(static function (CartItem $item) {
            return ProductDto::productMapping($item->getProduct());
        }, $cart->getItems()->toArray());

        return ['productsCount' => $productCount, 'productsPrice' => $productPrice, 'productsList' => $productsDto];
    }

    public function addItem(Product $product): void
    {
        $cartItem = (new CartItem())->setProduct($product)->setQuantity(1);
        $cart = $this->getCurrentCart();
        $cart->addItem($cartItem);
        $this->save($cart);
    }

    public function removeItem(Product $product): void
    {
        $cart = $this->getCurrentCart();
        $cartItem = $cart->getItems()->findFirst(function (int $key, CartItem $item) use ($product) {
            return $item->getProduct()?->getId() === $product->getId();
        });
        $itemCount = $cartItem->getQuantity() - 1;
        $itemCount < 1 ? $cart->removeItem($cartItem) : $cartItem->setQuantity($cartItem->getQuantity() - 1);
        $this->save($cart);
    }

    //@todo fix or remove CartRepository
    public function save(Cart $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }
}
