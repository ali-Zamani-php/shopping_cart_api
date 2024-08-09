<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, ShoppingCartItem>
     */
    #[ORM\OneToMany(targetEntity: ShoppingCartItem::class, mappedBy: 'shopping_cart_id', orphanRemoval: true)]
    private Collection $shoppingCartItems;

    public function __construct()
    {
        $this->shoppingCartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ShoppingCartItem>
     */
    public function getShoppingCartItems(): Collection
    {
        return $this->shoppingCartItems;
    }

    public function addShoppingCartItem(ShoppingCartItem $shoppingCartItem): static
    {
        if (!$this->shoppingCartItems->contains($shoppingCartItem)) {
            $this->shoppingCartItems->add($shoppingCartItem);
            $shoppingCartItem->setShoppingCartId($this);
        }

        return $this;
    }

    public function removeShoppingCartItem(ShoppingCartItem $shoppingCartItem): static
    {
        if ($this->shoppingCartItems->removeElement($shoppingCartItem)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartItem->getShoppingCartId() === $this) {
                $shoppingCartItem->setShoppingCartId(null);
            }
        }

        return $this;
    }
}
