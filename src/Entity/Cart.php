<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[Ignore]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'cartRef', targetEntity: CartItem::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(CartItem $item): self
    {
        foreach ($this->getItems() as $existingItem) {
            // The item already exists, update the quantity
            if ($existingItem->equals($item)) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return $this;
            }
        }

        $this->items[] = $item;
        $item->setCartRef($this);

        return $this;
    }

    public function removeItem(CartItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getCartRef() === $this) {
                $item->setCartRef(null);
            }
        }

        return $this;
    }

    public function getTotalCount(): int
    {
        return array_reduce($this->getItems()->toArray(), static function (int $acc, CartItem $item) {
            $acc += $item->getQuantity();
            return $acc;
        }, 0);
    }

    public function getTotalPrice(): float
    {
        return array_reduce($this->getItems()->toArray(), static function (float $acc, CartItem $item) {
            $acc += $item->getTotalPrice();
            return $acc;
        }, 0);
    }
}
