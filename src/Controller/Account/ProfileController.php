<?php

namespace App\Controller\Account;

use App\Form\ProfilFormType;
use App\Form\RemoveAccountFormType;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/')]
class ProfileController extends AbstractController
{
    #[Route('/profil/mes_informations', name: 'app_profile_index')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            "user" => $user,

        ]);
    }

    #[Route('/profil/mes_informations/modifier', name: 'app_profile_edit_informations')]
    public function modifierInformations(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerService $mailerService): Response
    {
        $user = $this->getUser();

        // ternaire afin de déterminer le contexte (si ROLE_ECURIE alors contexte ecurie sinon contexte gite)
        $context = $this->isGranted('ROLE_ECURIE') ? 'ecurie' : 'gite';

        $profilForm = $this->createForm(ProfilFormType::class, $user, ['context' => $context]);

        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {

            $mailerService->send(
                $user->getEmail(),
                'Prise en compte des modifications de vos données personnelles',
                'mails/edit_informations.html.twig',
                [
                    'firstName' => $user->getCustomer()->getFirstName(),
                    'lastName' => $user->getCustomer()->getLastName()
                ]

            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vos modifications ont bien été prises en compte');

            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/edit_informations_user.html.twig', [
            'profilForm' => $profilForm->createView(),
            "user" => $user

        ]);
    }

    #[Route('/profil/mes_informations/supprimer_mon_compte', name: 'app_profile_remove_account')]
    public function supprimerCompte(
        Request $request,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        $removeAccountForm = $this->createForm(RemoveAccountFormType::class, $user);
        $removeAccountForm->handleRequest($request);

        if ($removeAccountForm->isSubmitted() && $removeAccountForm->isValid()) {
            $entityManager->remove($user);
            $entityManager->flush();

            //je déconnecte l'utilisateur en effaçant le jeton d'authentification actuel
            $tokenStorage->setToken(null);

            $this->addFlash('success', 'Votre compte a été supprimé avec succès !');

            return $this->redirectToRoute('app_profile_remove_account');
        }
        return  $this->render('profile/remove_account.html.twig', [
            'removeAccountForm' => $removeAccountForm->createView(),
        ]);

    }
}
