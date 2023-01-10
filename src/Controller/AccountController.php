<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ManageProfileType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/account/', name:'account_')]
class AccountController extends AbstractController
{
    #[Route('profile', name: 'profile')]
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

        // if($form->isSubmitted()) {
        //     if($newPW = $form->get('password')->getData()) {
        //         $user->setPassword(
        //             $userPasswordHasher->hashPassword(
        //                 $user,
        //                 $newPW
        //             )
        //         );
        //     }
        // }
        
        return $this->render('account/profile.html.twig', [
            'manageProfile' => $form->createView(),
        ]);
    }
}
