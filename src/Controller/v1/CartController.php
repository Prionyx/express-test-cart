<?php

namespace App\Controller\v1;

use App\Cart\CartManager;
use App\Repository\ProductRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private ProductRepository $productRepository;
    private CartManager $cartManager;

    public function __construct(
        ProductRepository $productRepository,
        CartManager $cartManager,
    ) {
        $this->productRepository = $productRepository;
        $this->cartManager = $cartManager;
    }

    #[Route('/api/v1/cart/', name: 'app_get_cart', methods: ['GET'])]
    public function getCart(): JsonResponse
    {
        try {
            $cart = $this->cartManager->getCurrentCartWithProducts();
            return $this->json($cart);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/v1/cart/{productId}', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(int $productId): JsonResponse
    {
        try {
            $product = $this->productRepository->find($productId);
            if (!$product) {
                throw new NotFoundHttpException('Product not found');
            }
            $this->cartManager->addItem($product);
            return $this->json([], Response::HTTP_CREATED);
        } catch (HttpExceptionInterface $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/v1/cart/{productId}', name: 'app_delete_from_cart', methods: ['DELETE'])]
    public function deleteFromCart(int $productId): JsonResponse
    {
        try {
            $product = $this->productRepository->find($productId);
            if (!$product) {
                throw new NotFoundHttpException('Product not found');
            }
            $this->cartManager->removeItem($product);
            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (HttpExceptionInterface $e) {
            return $this->json($e->getMessage(), $e->getStatusCode());
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
