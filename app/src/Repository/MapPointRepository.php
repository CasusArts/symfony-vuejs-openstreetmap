<?php

namespace App\Repository;

use App\Entity\MapPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method MapPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method MapPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method MapPoint[]    findAll()
 * @method MapPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MapPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapPoint::class);
    }

    public function getActiveMapPoints(?array $checkedCategories, ?array $mapBounds, int $page)
    {
        $itemsPerPage = 30;
        $query =  $this->createQueryBuilder('m')
            ->select('partial m.{id, title, description, lat, lng, color, logo, uploadDir, rating, numberOfReviews}', 'partial u.{id}', 'partial c.{id, name}')
            ->innerJoin('m.user', 'u')
            ->leftJoin('m.mapPointCategories', 'c', 'WITH', 'c.isActive = 1')
            ->where('m.isActive = 1')
            ->orderBy('m.id', 'DESC')
            ->setFirstResult($itemsPerPage * ($page -1))
            ->setMaxResults($itemsPerPage)
        ;

        if (!empty($checkedCategories)) {
            $query->andWhere('c.id IN(:categories)');
            $query->setParameter('categories', $checkedCategories);
        }

        if (!empty($mapBounds)) {
            $query->andWhere('m.lat > :southWestLat AND m.lat < :northEastLat');
            $query->andWhere('m.lng > :southWestLng AND m.lng < :northEastLng');
            $query->setParameter('southWestLat', $mapBounds['southWest']['lat']);
            $query->setParameter('southWestLng', $mapBounds['southWest']['lng']);
            $query->setParameter('northEastLat', $mapBounds['northEast']['lat']);
            $query->setParameter('northEastLng', $mapBounds['northEast']['lng']);
        }

        $queryHydrateArray = $query->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY);

        $paginator = new Paginator($queryHydrateArray, true);
        $iterator = $paginator->getIterator();

        /** @var ArrayIterator $iterator */
        return $iterator->getArrayCopy();
    }

    public function getMapPointById(int $id)
    {
        return $this->createQueryBuilder('m')
            ->select('m', 'i', 'partial u.{id, name}', 'partial c.{id, name}')
            ->innerJoin('m.user', 'u')
            ->leftJoin('m.mapPointImage', 'i')
            ->leftJoin('m.mapPointCategories', 'c', 'WITH', 'c.isActive = 1')
            ->where('m.id = :id')
            ->andWhere('m.isActive = 1')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult(Query::HYDRATE_ARRAY)
        ;
    }

    public function countInactiveMapPoints()
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.isActive = 0')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getLastAddedMapPoints()
    {
        $query =  $this->createQueryBuilder('m')
            ->select('partial m.{id, title, postcode, city, street, logo, uploadDir, createdAt, isActive}', 'partial u.{id, name}', 'partial c.{id, name}')
            ->innerJoin('m.user', 'u')
            ->leftJoin('m.mapPointCategories', 'c')
            ->orderBy('m.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(3)
        ;

        $queryHydrateArray = $query->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY);

        $paginator = new Paginator($queryHydrateArray, true);
        $iterator = $paginator->getIterator();

        /** @var ArrayIterator $iterator */
        return $iterator->getArrayCopy();
    }

    public function getAllMapPoints(int $page, ?bool $status)
    {
        $itemsPerPage = 10;
        $query =  $this->createQueryBuilder('m')
            ->select('partial m.{id, title, city, rating, numberOfReviews, isActive, createdAt}', 'partial u.{id, name}')
            ->innerJoin('m.user', 'u')
            ->orderBy('m.id', 'DESC')
            ->setFirstResult($itemsPerPage * ($page -1))
            ->setMaxResults($itemsPerPage)
        ;

        if (!is_null($status)) {
            $query->andWhere('m.isActive = :status');
            $query->setParameter('status', $status);
        }

        $queryHydrateArray = $query->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY);

        $paginator = new Paginator($queryHydrateArray, true);
        $iterator = $paginator->getIterator();
        $totalItems = $paginator->count();

        /** @var ArrayIterator $iterator */
        return [
            'mapPoints' => $iterator->getArrayCopy(),
            'itemsPerPage' => $itemsPerPage,
            'totalItems' => $totalItems,
        ];
    }

    // /**
    //  * @return MapPoint[] Returns an array of MapPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MapPoint
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
