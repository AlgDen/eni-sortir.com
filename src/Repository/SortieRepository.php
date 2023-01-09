<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{

    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        $this->security = $security;
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function researchSortie(Sortie $entity, $dateDebut, $dateFin, $option1, $option2, $option3, $option4): array
    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.nom LIKE :vNom')
//            ->setParameter('vNom', '%' . $entity->getNom() . '%')
//            ->andWhere('s.date BETWEEN :dateD AND :dateF')
//            ->setParameter('dateD', $dateDebut)
//            ->setParameter('dateF', $dateFin)
//            ->orderBy('s.id', 'ASC')
//            ->getQuery()
//            ->getResult();

        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.inscrits', 'i');
        if ($entity->getLieu()) {
            if ($entity->getLieu()->getNom() !== null || $entity->getLieu()->getNom() !== "") {
//            $qb->andWhere('s.lieu ')
            }
        }
        if ($entity->getNom() !== null) {
            $qb = $qb->andWhere('s.nom = :vNom')
                ->setParameter('vNom', $entity->getNom());
        }
        $user = $this->security->getUser();
        if ($option1) {
            $qb = $qb->andWhere('s.Organisateur = :user')
                ->setParameter('user', $user);
        }
//        if ($option2) {
//            $qb = $qb->andWhere('s.inscrits ')
//        }
        $qb = $qb->andWhere('s.date BETWEEN :dateD AND :dateF')
            ->setParameter('dateD', $dateDebut)
            ->setParameter('dateF', $dateFin)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $entity)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        dd($qb);
        return $qb;
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
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

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
