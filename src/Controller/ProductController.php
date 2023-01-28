<?php

namespace App\Controller;

use App\Controller\Dto\ProductDto;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/api/product', name: 'app_product', methods: ["GET"])]
    public function getProducts(Request $request): JsonResponse
    {
        $typeId = $request->query->get('type');
        $products = $typeId ? $this->productRepository->findByType($typeId) : $this->productRepository->findAll();
        //@todo Упростить array_map
        $productsDto = array_map(static function ($product) {
            return ProductDto::productMapping($product);
        }, $products);
        return $this->json($productsDto);
    }
}
