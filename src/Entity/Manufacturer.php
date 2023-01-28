<?php

namespace App\Entity;

use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManufacturerRepository::class)]
#[ORM\UniqueConstraint(
    name: 'manufacturer_unique_name',
    columns: ['name']
)]
class Manufacturer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'manufacturer', targetEntity: ProductModel::class)]
    private Collection $product_model;

    public function __construct()
    {
        $this->product_model = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ProductModel>
     */
    public function getProductModel(): Collection
    {
        return $this->product_model;
    }

    public function addProductModel(ProductModel $productModel): self
    {
        if (!$this->product_model->contains($productModel)) {
            $this->product_model->add($productModel);
            $productModel->setManufacturer($this);
        }

        return $this;
    }

    public function removeProductModel(ProductModel $productModel): self
    {
        if ($this->product_model->removeElement($productModel)) {
            // set the owning side to null (unless already changed)
            if ($productModel->getManufacturer() === $this) {
                $productModel->setManufacturer(null);
            }
        }

        return $this;
    }
}
