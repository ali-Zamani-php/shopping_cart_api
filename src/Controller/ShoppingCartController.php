<?php

namespace App\Controller;

use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1', name: 'shopping_cart_')]
class ShoppingCartController extends AbstractController
{

    private ShoppingCartRepository $shoppingCarts;

    /**
     * @param ShoppingCartRepository $shoppingCarts
     * @param EntityManagerInterface $objectManager
     * @param SerializerInterface $serializer
     */
    public function __construct( ShoppingCartRepository $shoppingCarts)
    {
        $this->shoppingCarts = $shoppingCarts;
    }

    #[Route('/shopping-cart', name: 'create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        $shoppingCart = $this->shoppingCarts->createShoppingCart();
       return $this->json($shoppingCart,JsonResponse::HTTP_CREATED,[],['groups' => ['shoppingCart:read']]);
    }

    #[Route('/shopping-cart/{shoppping_cart_id}', name: 'show', requirements: ['shoppping_cart_id' => '\d+'], methods: ['GET']), ]
    public function show($shoppping_cart_id): JsonResponse
    {
        $shoppingCart = $this->shoppingCarts->findWithItems($shoppping_cart_id);
        if (!$shoppingCart) {
            throw $this->createNotFoundException(
                'No product found for id ' . $shoppping_cart_id
            );
        }
        return $this->json(
            $shoppingCart,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['shoppingCart:read']]
        );
    }

}