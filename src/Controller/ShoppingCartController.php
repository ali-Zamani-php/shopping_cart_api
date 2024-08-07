<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShoppingCartController extends AbstractController
{
    #[Route('/shopping-cart', name: 'shopping_cart_create', methods: ['POST'])]
    public function create(): Response
    {

    }
}