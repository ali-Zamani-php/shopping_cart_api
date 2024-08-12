<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoppingCartItem>
 */
class ShoppingCartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingCartItem::class);
    }

    public function addItemToCart(ShoppingCart $cart, Item $item): ?ShoppingCartItem
    {
        foreach ($cart->getShoppingCartItems() as $shoppingCartItem) {
            if ($shoppingCartItem->getItem() === $item) {
                $quantity = $shoppingCartItem->getQuantity() + 1;
                $shoppingCartItem->setQuantity($quantity);
                $this->getEntityManager()->persist($shoppingCartItem);
                $this->getEntityManager()->flush();
                return $shoppingCartItem;
            }
        }
        $shoppingCartItem = new ShoppingCartItem();
        $shoppingCartItem->setShoppingCart($cart);
        $shoppingCartItem->setItem($item);
        $this->getEntityManager()->persist($shoppingCartItem);
        $this->getEntityManager()->flush();
        return $shoppingCartItem;
    }

    public function removeItemFromCart(ShoppingCart $cart, Item $item)
    {
        $entityManager = $this->getEntityManager();
        $shoppingCartItem = $this->findOneBy([
            'shoppingCart' => $cart,
            'item' => $item,
        ]);
        if ($shoppingCartItem) {
            $quantity = $shoppingCartItem->getQuantity();
            if ($quantity > 1) {
                $shoppingCartItem->setQuantity(--$quantity);
                $entityManager->persist($shoppingCartItem);
            } else {
                $entityManager->remove($shoppingCartItem);
            }
            $entityManager->flush();
        }
    }

    public function updateCartItem(ShoppingCart $cart, Item $item, int $quantity)
    {
        $entityManager = $this->getEntityManager();
        $shoppingCartItem = $this->findOneBy([
            'shoppingCart' => $cart,
            'item' => $item,
        ]);
        if ($shoppingCartItem) {
            $shoppingCartItem->setQuantity($quantity);
            $entityManager->persist($shoppingCartItem);
            $entityManager->flush();
        }
        return $shoppingCartItem;
    }

    //    /**
    //     * @return ShoppingCartItem[] Returns an array of ShoppingCartItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ShoppingCartItem
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
