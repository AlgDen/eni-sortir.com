<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\CreerSortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreerSortieController extends AbstractController
{
    #[Route('/', name: 'app_creer_sortie')]
    public function creerSortie(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(CreerSortieType::class, $sortie);
        $form->handleRequest($request);

        return $this->render('creer_sortie/index.html.twig', [
            'creerSortieForm' => $form,
        ]);

    }
}
