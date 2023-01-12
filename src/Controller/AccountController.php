<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ManageProfileType;
use App\Form\ParticipantType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

use function PHPUnit\Framework\throwException;

class AccountController extends AbstractController
{
    #[Route('/account/profile', name: 'account_profile')]
    public function profile(Request $req, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, SiteRepository $siteRepository, UserInterface $user): Response
    {
        // initialize form
        $form = $this->createForm(ManageProfileType::class, $user);
        // get request
        $form->handleRequest($req);
        
        // submit data if request is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // file upload

            if($newPW = $form->get('password')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $newPW
                    )
                );
            }

            
            $entityManager->persist($user);
            $entityManager->flush();
        }
        
        return $this->render('account/profile.html.twig', [
            'manageProfile' => $form->createView(),
        ]);
    }
    
    #[Route('/sortie/{idSortie}/participant/{idUser}', name: 'details_participant')]
    public function detailsParticipant(SortieRepository $sortieRepository, EntityManagerInterface $em, UserRepository $userRepository, int $idSortie, int $idUser, UserInterface $userInterface) {
        // get sortie by id
        $sortie = $sortieRepository->find($idSortie);
        
        // get user by id
        $visitedUser = $userRepository->find($idUser);

        // get connected user
        $connectedUser = $userInterface;

        // sortie not found || user not found || visited user not registered in sortie
        if(!$sortie || !$visitedUser || !$sortie->getInscrits()->contains($visitedUser)) {
            throw new NotFoundHttpException('404 Resource not found');
            exit;
        }
        
        // rediriger 401 si l'utilisateur n'est pas participant de la sortie 
        if(!$sortie->getInscrits()->contains($connectedUser) && $connectedUser->getId() != $sortie->getOrganisateur()->getId()) {
            throw new UnauthorizedHttpException('401 Unauthorized');
            exit;
        }

        return $this->render('account/detailsParticipant.html.twig', [
            'user' => $visitedUser
        ]);
    }
}
