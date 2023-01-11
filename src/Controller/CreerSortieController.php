<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\CreerSortieType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class CreerSortieController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/creerSortie', name: 'app_creer_sortie')]
    public function creerSortie(Request $request,EntityManagerInterface $entityManager, EtatRepository $etatRepository,UserRepository $userRepository): Response
    {

        $sortie = new Sortie();
        $form = $this->createForm(CreerSortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sortie = $form->getData();
            if ($form->getClickedButton() && 'enregistrer' === $form->getClickedButton()->getName()) {
                $sortie->setEtat($etatRepository->find(1));
            }
            else{
                $sortie->setEtat($etatRepository->find(4));
            }
            $sortie->setOrganisateur($userRepository->findOneBy(array('email' => $this->security->getUser()->getUserIdentifier())));
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_creer_sortie');
        }

        return $this->render('creer_sortie/index.html.twig', [
            'creerSortieForm' => $form,
        ]);

    }

    #[Route('/lieuData', name: 'app_lieu_data')]
    public function getLieuData(Request $request, EntityManagerInterface $entityManager, LieuRepository $lieuRepository, VilleRepository $villeRepository): JsonResponse
    {
        $lieu = $lieuRepository->find($request->get("idLieu"));
        $ville = $villeRepository->find($lieu->getVille()->getId());
//        dd($lieu);
//        dd($request->get("idLieu"));
        return new JsonResponse(array("nom"=>$lieu->getNom(),"ville"=>$ville->getNom(),"cp"=>$ville->getCodePostal(),"rue" => $lieu->getRue(),"latitude" => $lieu->getLatitude(),"longitude"=>$lieu->getLongitude()));
    }
}
