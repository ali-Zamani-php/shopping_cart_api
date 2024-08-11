<?php

namespace App\Controller;

use App\Entity\ShoppingCartItem;
use App\Repository\ItemRepository;
use App\Repository\ShoppingCartItemRepository;
use App\Repository\ShoppingCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/api/v1', name: 'shopping_cart_item_')]
class ShoppingCartItemController extends AbstractController
{
    private ShoppingCartRepository $shoppingCartRepository;
    private ItemRepository $itemRepository;
    private ShoppingCartItemRepository $shoppingCartItemRepository;

    public function __construct(
        ShoppingCartRepository     $shoppingCartRepository,
        ItemRepository             $itemRepository,
        ShoppingCartItemRepository $shoppingCartItemRepository
    )
    {
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->itemRepository = $itemRepository;
        $this->shoppingCartItemRepository = $shoppingCartItemRepository;
    }

    #[Route('/shopping-cart/{shoppingCartId}/item/{itemId}',
        name: 'create',
        requirements: ['shoppingCartId' => '\d+', 'itemId' => '\d+'],
        methods: ['POST'])]
    public function create($shoppingCartId, $itemId): JsonResponse
    {
        $item = $this->itemRepository->find($itemId);
        if (!$item) {
            throw $this->createNotFoundException(
                'No Item found for id ' . $itemId
            );
        }
        $shoppingCart = $this->shoppingCartRepository->find($shoppingCartId);
        if (!$shoppingCart) {
            throw $this->createNotFoundException(
                'No Cart found for id ' . $shoppingCartId
            );
        }
        $shoppingCartItem = $this->shoppingCartItemRepository->addItemToCart($shoppingCart, $item);
        return $this->json($shoppingCartItem, JsonResponse::HTTP_CREATED, [], ['groups' => ['shoppingCart:read']]);
    }

    #[Route('/shopping-cart/{shoppingCartId}/item/{itemId}',
        name: 'update',
        requirements: ['shoppingCartId' => '\d+', 'itemId' => '\d+'],
        methods: ['PUT'])]
    public function update(Request $request, $shoppingCartId, $itemId, ValidatorInterface $validator): JsonResponse
    {
        $item = $this->itemRepository->find($itemId);
        if (!$item) {
            throw $this->createNotFoundException(
                'No Item found for id ' . $itemId
            );
        }
        $shoppingCart = $this->shoppingCartRepository->find($shoppingCartId);
        if (!$shoppingCart) {
            throw $this->createNotFoundException(
                'No Cart found for id ' . $shoppingCartId
            );
        }
        $data = json_decode($request->getContent(), true);
        $constraints = new Assert\Collection([
            'quantity' => [new Assert\NotBlank(), new Assert\Positive()],
        ]);
        $errors = $validator->validate($data, $constraints);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return $this->json(['error' => $errorsString], JsonResponse::HTTP_BAD_REQUEST);
        }
        $shoppingCartItem = $this->shoppingCartItemRepository->updateCartItem($shoppingCart, $item, $data['quantity']);
        return $this->json($shoppingCartItem,
            JsonResponse::HTTP_OK, [], ['groups' => ['shoppingCart:read']]);
    }

    #[Route('/shopping-cart/{shoppingCartId}/item/{itemId}',
        name: 'remove',
        requirements: ['shoppingCartId' => '\d+', 'itemId' => '\d+'],
        methods: ['DELETE'])]
    public function remove($shoppingCartId, $itemId)
    {
        $item = $this->itemRepository->find($itemId);
        if (!$item) {
            throw $this->createNotFoundException(
                'No Item found for id ' . $itemId
            );
        }
        $shoppingCart = $this->shoppingCartRepository->find($shoppingCartId);
        if (!$shoppingCart) {
            throw $this->createNotFoundException(
                'No Cart found for id ' . $shoppingCartId
            );
        }
        $this->shoppingCartItemRepository->removeItemFromCart($shoppingCart, $item);
        return $this->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
