<?php

namespace App\Controller;

use App\CartManager;
use App\Controller\Dto\ProductDto;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private ProductRepository $productRepository;
    private CartRepository $cartRepository;
    private CartManager $cartManager;

    public function __construct(
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        CartManager $cartManager
    )
    {
        $this->cart = new Cart();
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->cartManager = $cartManager;
    }

    //@todo /api организовать
    #[Route('/api/cart/', name: 'app_get_cart', methods: ['GET'])]
    public function getCart(): JsonResponse
    {
        $cartData = $this->cartManager->getCartData();
        return $this->json($cartData);
    }

    //@todo PUT или POST?
    #[Route('/api/cart/{productId}', name: 'app_add_to_cart', methods: ['PUT'])]
    public function addToCart(int $productId): JsonResponse
    {
        //@todo проверка получаемых данных
        $product = $this->productRepository->find($productId);
        if (!$product) {
            //@todo Дописать ошибку, уточнить класс
            throw new NotFoundHttpException('Нет товара');
        }

        $this->cartManager->addItem($product);

        //@todo добавить нормальный статус
        return $this->json('ok');
    }

    #[Route('/api/cart/{productId}', name: 'app_delete_from_cart', methods: ['DELETE'])]
    public function deleteFromCart(int $productId): JsonResponse
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            //@todo Дописать ошибку, уточнить класс
            throw new NotFoundHttpException('Нет товара');
        }

        $this->cartManager->removeItem($product);

        //@todo добавить нормальный статус
        return $this->json('ok');
    }
}