<?php

namespace App\Controller;

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
//        $sortieRepository->
        $form = $this->createForm(AfficherSortiesType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $data = $form->getData();
            var_dump($data);
            var_dump($form->getData());
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_sortie');
        }

        return $this->render('sortie/index.html.twig', [
            'form' => $form,
        ]);
    }
}
