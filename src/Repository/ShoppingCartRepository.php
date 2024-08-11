<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ShoppingCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoppingCart>
 */
class ShoppingCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoppingCart::class);
    }
    public function findWithItems(int $id): ?ShoppingCart
    {
        $qb = $this->createQueryBuilder('sc')
        ->leftJoin('sc.shoppingCartItems', 'sci')
        ->addSelect('sci')
        ->leftJoin('sci.item', 'i')
        ->where('sc.id = :id')
        ->setParameter('id', $id);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function createShoppingCart(): ShoppingCart
    {
        $shoppingCart = new ShoppingCart();
        $this->getEntityManager()->persist($shoppingCart);
        $this->getEntityManager()->flush();

        return $shoppingCart;
    }



    //    /**
    //     * @return ShoppingCart[] Returns an array of ShoppingCart objects
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

    //    public function findOneBySomeField($value): ?ShoppingCart
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
