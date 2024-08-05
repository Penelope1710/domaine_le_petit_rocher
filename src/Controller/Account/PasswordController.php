<?php

namespace App\Controller\Account;


use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{
    /**
     * @throws LogicException
     */
    #[Route('/profil/mes_informations/modifier_mot_de_passe', name: 'app_password_edit')]
    public function edit(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $passwordUserForm = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $passwordUserForm->handleRequest($request);

        if ($passwordUserForm->isSubmitted() && $passwordUserForm->isValid()) {
            $entityManager->flush();
        }

        return $this->render('profile/password_edit.html.twig', [
            'modifyPassword' => $passwordUserForm->createView()
        ]);
    }
}