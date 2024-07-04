<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    //on injecte une dependance request avec une variable 
    public function index(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();
            $this->addFlash(
                'success',
                'Votre compte à bien été creer! Vous pouvez à present vous connecter'
            );
            return $this->redirectToRoute('app_login');
        }
        //si le formulaire est soumit
        //enregistre les data
        //envoie de confirmation si le compte est bien creer 

        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
