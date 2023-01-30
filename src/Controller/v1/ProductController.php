<?php

namespace App\Controller\v1;

use App\Repository\ProductRepository;
use App\Validators\ProductRequestValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private ProductRequestValidator $productRequestValidator;

    public function __construct(ProductRepository $productRepository, ProductRequestValidator $productRequestValidator)
    {
        $this->productRepository = $productRepository;
        $this->productRequestValidator = $productRequestValidator;
    }

    #[Route('/api/v1/product', name: 'app_product', methods: ["GET"])]
    public function getProducts(Request $request): JsonResponse
    {
        try {
            $this->productRequestValidator->validate($request->query->all());
            $typeId = $request->query->get('type');
            $products = $typeId ? $this->productRepository->findByType($typeId) : $this->productRepository->findAll();

            return $this->json($products, context: [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getName();
            }]);
        } catch (InvalidParameterException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
