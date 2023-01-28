<?php

namespace App\DataFixtures;

use App\Entity\Manufacturer;
use App\Entity\Product;
use App\Entity\ProductModel;
use App\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class ProductFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $productTypeList = [];
        for ($i = 1; $i <= 2; $i++) {
            $productType = new ProductType();
            $productType->setName('Type_'. $i);
            $productTypeList[] = $productType;

            $manager->persist($productType);
        }

        $manufacturerList = [];
        for ($i = 1; $i <= 2; $i++) {
            $manufacturer = new Manufacturer();
            $manufacturer->setName('Manufacturer_'. $i);
            $manufacturerList[] = $manufacturer;

            $manager->persist($manufacturer);
        }

        $productModelList = [];
        for ($i = 1; $i <= 4; $i++) {
            $productModel = new ProductModel();
            $productModel->setName('ProductModel_'. $i);
            $productModel->setManufacturer($manufacturerList[$i % 2]);
            $productModel->setProductType($productTypeList[$i % 2]);
            $productModelList[] = $productModel;

            $manager->persist($productModel);
        }

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product
                ->setName('Product_' . $i)
                ->setProductModel($productModelList[$i % 4])
                ->setPrice(random_int(10, 100));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
