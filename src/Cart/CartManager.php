<?php

namespace App\Cart;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartManager
{
    private EntityManagerInterface $entityManager;
    private CartSessionStorage $cartSessionStorage;
    private CartFactory $cartFactory;

    public function __construct(EntityManagerInterface $entityManager, CartSessionStorage $cartSessionStorage, CartFactory $cartFactory)
    {
        $this->entityManager = $entityManager;
        $this->cartSessionStorage = $cartSessionStorage;
        $this->cartFactory = $cartFactory;
    }

    public function getCurrentCart(): Cart
    {
        return $this->cartSessionStorage->getCart() ?? $this->cartFactory->create();
    }

    public function getCurrentCartWithProducts(): Cart
    {
        return $this->cartSessionStorage->getCartWithProducts() ?? $this->cartFactory->create();
    }

    public function addItem(Product $product): void
    {
        $cartItem = $this->cartFactory->createItem($product);
        $cart = $this->getCurrentCart();
        $cart->addItem($cartItem);
        $this->save($cart);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function removeItem(Product $product): void
    {
        $cart = $this->getCurrentCart();
        $cartItem = $cart->getItems()->findFirst(function (int $key, CartItem $item) use ($product) {
            return $item->getProduct()?->getId() === $product->getId();
        });
        if (!$cartItem) {
            throw new NotFoundHttpException('Product not in cart');
        }
        $itemCount = $cartItem->getQuantity() - 1;
        $itemCount < 1 ? $cart->removeItem($cartItem) : $cartItem->setQuantity($cartItem->getQuantity() - 1);
        $this->save($cart);
    }

    public function save(Cart $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }
}
