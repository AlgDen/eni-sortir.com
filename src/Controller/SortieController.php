<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AfficherSortiesType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/', name: 'app_sortie')]
    public function index(Request $request, SortieRepository $sortieRepository): Response
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

        if ($form->isSubmitted()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $data = $form->getData();
            $dateDebut = $form->get('dateDebut')->getData();
            $dateFin = $form->get('dateFin')->getData();
            $option1 = $form->get('option1')->getData();
            $option2 = $form->get('option2')->getData();
            $option3 = $form->get('option3')->getData();
            $option4 = $form->get('option4')->getData();

            $data = $sortieRepository->researchSortie($data, $dateDebut, $dateFin, $option1, $option2, $option3, $option4);

//            return $this->redirectToRoute('app_sortie');
        }

        dump($data);
        if($data == null){
            $data = $sortieRepository->findAll();
        }
        //TODO: if data is null then take find all, else take search from form
        return $this->render('sortie/index.html.twig', [
            'form' => $form,
            'data' => $data
        ]);
    }
}
