<?php

namespace App\Entity;

use App\Repository\ShoppingCartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ShoppingCartItemRepository::class)]
class ShoppingCartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['shoppingCart:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingCartItems')]
    private ?ShoppingCart $shoppingCart = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingCartItems')]
    #[Groups(['shoppingCart:read'])]
    private ?Item $item = null;

    #[ORM\Column]
    #[Groups(['shoppingCart:read'])]
    #[Assert\Positive]
    private ?int $quantity = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShoppingCart(): ?ShoppingCart
    {
        return $this->shoppingCart;
    }

    public function setShoppingCart(?ShoppingCart $shoppingCart): static
    {
        $this->shoppingCart = $shoppingCart;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }
}
