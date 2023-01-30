<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[Ignore]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[Ignore]
    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cartRef = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function equals(CartItem $item): bool
    {
        return $this->getProduct()?->getId() === $item->getProduct()?->getId();
    }

    public function getCartRef(): ?Cart
    {
        return $this->cartRef;
    }

    public function setCartRef(?Cart $cartRef): self
    {
        $this->cartRef = $cartRef;

        return $this;
    }

    #[Ignore]
    public function getTotalPrice(): float
    {
        return (float) $this->getProduct()?->getPrice() * $this->getQuantity();
    }
}
