<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RemoveAccountFormType;
use App\Repository\UserRepository;
use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
#[Route('/')]
class EcurieController extends AbstractController
{
    #[Route('/ecurie/presentation', name: 'ecurie_presentation')]
    public function presentation(): Response
    {
        return $this->render('ecurie/public/presentation.html.twig');
    }

    #[Route('/prive/ecurie', name: 'ecurie_home')]
    public function home(): Response
    {
        return $this->render('ecurie/prive/home.html.twig');
    }

    #[Route('/prive/ecurie/mes_informations', name: 'ecurie_mes_informations')]
    public function Informations(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $profilForm = $this->createForm(ProfilFormType::class, $user, ['context' => 'ecurie']);

        $profilForm->handleRequest($request);

        if ($profilForm->isSubmitted() && $profilForm->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

           return $this->redirectToRoute('ecurie_mes_informations');
        }

            return $this->render('ecurie/prive/mes_informations.html.twig', [
                'profilForm' => $profilForm->createView(),
                "user" => $user

            ]);
        }

        #[Route('/prive/ecurie/mes_informations/supprimer_mon_compte', name: 'ecurie_supprimer_compte')]
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

                $tokenStorage->setToken(null);

                $this->addFlash('success', 'Votre compte a été supprimé avec succès !');

                return $this->redirectToRoute('ecurie_supprimer_compte');
            }
         return  $this->render('ecurie/prive/removeAccount.html.twig', [
            'removeAccountForm' => $removeAccountForm->createView(),
             ]);

        }

    }
