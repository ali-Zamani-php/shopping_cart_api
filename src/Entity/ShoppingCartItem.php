<?php

namespace App\Entity;

use App\Repository\ShoppingCartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartItemRepository::class)]
class ShoppingCartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingCartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShoppingCart $shopping_cart_id = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingCartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShoppingCartId(): ?ShoppingCart
    {
        return $this->shopping_cart_id;
    }

    public function setShoppingCartId(?ShoppingCart $shopping_cart_id): static
    {
        $this->shopping_cart_id = $shopping_cart_id;

        return $this;
    }

    public function getItemId(): ?Item
    {
        return $this->item_id;
    }

    public function setItemId(?Item $item_id): static
    {
        $this->item_id = $item_id;

        return $this;
    }
}
