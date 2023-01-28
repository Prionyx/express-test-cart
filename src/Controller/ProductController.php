<?php

namespace App\Controller;

use App\Controller\Dto\ProductDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/api/product', name: 'app_product', methods: ["GET"])]
    public function getProducts(): JsonResponse
    {
        $products = $this->productRepository->findAll();
        $productsDto = array_map([$this, 'productMapping'], $products);
        return $this->json($productsDto);
    }

    //@todo Вынести
    private function productMapping(Product $product): ProductDto
    {
        return new ProductDto($product->getId(), $product->getName(), $product->getPrice());
    }
}
