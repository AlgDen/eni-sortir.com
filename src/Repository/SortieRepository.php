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

    public function researchSortie(Sortie $entity, $dateDebut, $dateFin, $option1, $option2, $option3, $option4, UserRepository $userRepository): array
    {
        $qb = $this->createQueryBuilder('s');
//            ->leftJoin('user', 'u');
        if ($entity->getLieu()) {
            if ($entity->getLieu()->getNom() !== null || $entity->getLieu()->getNom() !== "") {
            $qb = $qb->andWhere('s.lieu = :vLieu')
                ->setParameter('vLieu', $entity->getLieu());
            }
        }
        if ($entity->getNom() !== null) {
            $qb = $qb->andWhere('s.nom LIKE :vNom')
                ->setParameter('vNom', '%'.$entity->getNom().'%');
        }
        $user = $this->security->getUser();
        if ($option1) {
            $qb = $qb->andWhere('s.Organisateur = :user')
                ->setParameter('user', $userRepository->findOneBy(array('email' => $user->getUserIdentifier())));
        }
        if ($option2 && $user) {
            $qb = $qb->innerJoin('s.inscrits', 'su');
            $qb = $qb->andWhere('su.email = :user')
                ->setParameter('user', $user->getUserIdentifier());

//            $qb = $qb->andWhere('su.sortie_id = s.id')x²
//                ->andWhere('su. = u.id')
//                ->andWhere('u.email = :user')
//                ->setParameter('user', $user->getUserIdentifier());
        }
        if($option3) {
//            $qb = $qb->andWhere('s.inscrits = u.');
        }
        if ($option4){
            $qb = $qb->andWhere('s.etat = 6');
        }
        $qb = $qb->andWhere('s.etat != 7')
            ->andWhere('s.date BETWEEN :dateD AND :dateF')
            ->setParameter('dateD', $dateDebut->format('Y-m-d'))
            ->setParameter('dateF', $dateFin->format('Y-m-d')) //Y-m-d
            ->orderBy('s.id', 'ASC')
            ->orderBy('s.date', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        return $qb;
    }

    public function findAllSortie(){
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.etat != 7')
            ->orderBy('s.date', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
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
