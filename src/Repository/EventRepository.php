<?php

namespace App\Repository;

use App\Entity\Event;
use App\Data\SearchData;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
        $this->paginator = $paginator;
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Récupère les séances en fonction du filtre choisi
     * @return  PaginationInterface
     */
    public function findSearch(SearchData $searchData) : PaginationInterface {
        // On crée une requête qui récupère toutes les séances et qui les trie par id décroissant
        $query = $this
            ->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.id', 'DESC')
        ;

        // Si le champ de recherche q n'est pas vide,
        // alors on filtre les séances pour avoir celles qui contiennent le mot-clé
        if (!empty($searchData->q)) {
            $query = $query
                ->andWhere('e.title LIKE :q')
                ->orWhere('e.content LIKE :q')
                ->orWhere('e.instrument LIKE :q')
                ->orWhere('e.typeOfMusic LIKE :q')
                ->orWhere('e.meetingPlace LIKE :q')
                ->orWhere('e.meetingCity LIKE :q')
                ->orWhere('e.meetingPostalCode LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        // Si le champ de recherche postalCode n'est pas vide,
        // alors on filtre les séances pour avoir celles dont le code postal correspond
        if (!empty($searchData->postalCode)) {
            $query = $query
                ->andWhere('e.meetingPostalCode LIKE :postalCode')
                ->setParameter('postalCode', "%{$searchData->postalCode}%");
        }

        // Si le champ de recherche dateMin n'est pas vide,
        // alors on filtre les séances pour avoir celles dont la date est sup ou égale à dateMin
        if (!empty($searchData->dateMin)) {
            $query = $query
                ->andWhere('e.meetingDatetime >= :dateMin')
                ->setParameter('dateMin', $searchData->dateMin)
                ->orderBy('e.meetingDatetime', 'DESC');
        }

        // Si le champ de recherche dateMax n'est pas vide,
        // alors on filtre les séances pour avoir celles dont la date est inf ou égale à dateMax
        if (!empty($searchData->dateMax)) {
            $date = $searchData->dateMax;
            $datetime = $date->format('Y-m-d H:i:s');
            $stringDate = strtotime($datetime);
            $dateMax = date('Y-m-d H:i:s', strtotime('+ 23 hours 59 minutes', $stringDate));
            $query = $query
                ->andWhere('e.meetingDatetime <= :dateMax')
                ->setParameter('dateMax', $dateMax)
                ->orderBy('e.meetingDatetime', 'DESC');
        }

        // Si le champ de recherche instrument n'est pas vide,
        // alors on filtre les séances pour avoir celles dont l'instrument correspond
        if (!empty($searchData->instrument)) {
            $query = $query
                ->andWhere('e.instrument LIKE :instrument')
                ->setParameter('instrument', "%{$searchData->instrument}%");
        }

        // Si le champ de recherche typeOfMusic n'est pas vide,
        // alors on filtre les séances pour avoir celles dont le style de musique correspond
        if (!empty($searchData->typeOfMusic)) {
            $query = $query
                ->andWhere('e.typeOfMusic LIKE :style')
                ->setParameter('style', "%{$searchData->typeOfMusic}%");
        }

        $query = $query->getQuery();
        // on retourne les résultats dans une pagination
        // en partant de la page appelé via SearchData(par défaut 1)
        // et on met 5 résultats par page
        return $this->paginator->paginate($query, $searchData->page, 5);
    }
}
