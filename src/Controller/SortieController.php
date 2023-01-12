<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AfficherSortiesType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_sortie')]
    public function index(Request $request, SortieRepository $sortieRepository, UserRepository $userRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(AfficherSortiesType::class, $sortie);

        $form->handleRequest($request);

        /*
         * var form data
         * Sortie object
         * date / dateFin
         * Options 1 to 4
         */
        $data = null;
        $dateDebut = null;
        $dateFin = null;
        $option1 = null;
        $option2 = null;
        $option3 = null;
        $option4 = null;
        $empty = null;

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $dateDebut = $form->get('dateDebut')->getData();
            $dateFin = $form->get('dateFin')->getData();
            $option1 = $form->get('option1')->getData();
            $option2 = $form->get('option2')->getData();
            $option3 = $form->get('option3')->getData();
            $option4 = $form->get('option4')->getData();

            $data = $sortieRepository->researchSortie($data, $dateDebut, $dateFin, $option1, $option2, $option3, $option4, $userRepository);

            if ($data == null) {
                $empty = true;
            } else {
                $empty = false;
            }
//            return $this->redirectToRoute('app_sortie');
        }
        if($data == null){
            $data = $sortieRepository->findBy(array(), array('date' => 'ASC'), 10, 0);
        }
        return $this->render('sortie/index.html.twig', [
            'form' => $form,
            'data' => $data,
            'alert' => $empty
        ]);
    }

    #[Route('/inscription/{id}', name: 'app_sortie_inscription')]
    public function inscription(ManagerRegistry $doctrine, $id): Response{
        $entityManager = $doctrine->getManager();
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);
        $user = $entityManager->getRepository(User::class)->findOneBy(array('email' => $this->security->getUser()->getUserIdentifier()));
        if ($sortie->getInscrits()->contains($user)) {
            $sortie->removeInscrit($user);
            if($sortie->getDateLimiteInscription() > new \DateTime()){
                $sortie->setNbInscrits($sortie->getNbInscrits()-1);
            }
        } else {
            $sortie->addInscrit($user);
            $sortie->setNbInscrits($sortie->getNbInscrits()+1);
        }
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_sortie');
    }
}
