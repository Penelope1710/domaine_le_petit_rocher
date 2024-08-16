<?php

namespace App\Controller\Account;


use App\Entity\User;
use App\Form\PasswordUserType;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class PasswordController extends AbstractController
{
    /**
     * @throws LogicException
     */
    #[Route('/profil/mes_informations/modifier_mot_de_passe', name: 'app_password_edit')]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        MailerService $mailerService,
        User $user): Response
    {
        $user = $this->getUser();

        $passwordUserForm = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $passwordUserForm->handleRequest($request);

        if ($passwordUserForm->isSubmitted() && $passwordUserForm->isValid()) {

            $mailerService->send(
                $user->getEmail(),
                'Confirmation changement de votre mot de passe',
                'mails/edit_password.html.twig',
                [
                    'firstName' => $user->getCustomer()->getFirstName(),
                    'lastName' => $user->getCustomer()->getLastName()
                ]

            );

            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié');

            return $this->redirectToRoute('app_login');

        }

        return $this->render('profile/password_edit.html.twig', [
            'modifyPassword' => $passwordUserForm->createView()
        ]);
    }
}