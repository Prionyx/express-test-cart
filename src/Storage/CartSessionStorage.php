<?php

namespace App\Storage;

use App\Entity\Cart;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionStorage
{
    private const CART_KEY_NAME = 'cart_id';

    private RequestStack $requestStack;
    private CartRepository $cartRepository;

    public function __construct(RequestStack $requestStack, CartRepository $cartRepository)
    {
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
    }

    public function getCart(): ?Cart
    {
        return $this->cartRepository->findOneBy(['id' => $this->getCartId()]);
    }

    public function getCartWithProducts(): ?Cart
    {
        return $this->cartRepository->findWithProducts($this->getCartId());
    }

    public function setCart(Cart $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
