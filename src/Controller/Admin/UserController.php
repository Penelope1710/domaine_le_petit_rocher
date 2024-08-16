<?php

namespace App\Controller\Admin;

use App\Data\SearchUserData;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\Admin\AdminUserFormType;
use App\Form\SearchUserFormType;
use App\Repository\UserRepository;
use App\Services\FileUploadService;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Date;


#[Route('/admin', name: 'app_admin_')]
class UserController extends AbstractController
{
    #[Route('/home', name: 'user_home')]
    public function home(): Response
    {
        $currentDate = new \DateTime();

        return $this->render('admin/home/home.html.twig', [
            'currentDate' => $currentDate
        ]);
    }

    #[Route('/liste', name: 'users_list')]
    public function list(UserRepository $userRepository, Request $request)
    {
        $data = new SearchUserData();

        $searchUserFormType = $this->createForm(SearchUserFormType::class, $data);

        $searchUserFormType->handleRequest($request);
//        dd($data);
        //j'envoie les données de la recherche
        if ($searchUserFormType->isSubmitted() && $searchUserFormType->isValid()) {
            $data = $searchUserFormType->getData();
        }

        $pagination = $userRepository->findUserSearch($data, $request->query->get('page', 1));

        return $this->render('admin/users/list.html.twig', [
            'pagination' => $pagination,
            'searchUserForm' => $searchUserFormType->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'user_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request,
        MailerService $mailerService,
        FileUploadService $fileUploadService)
    {
        $context = $user->hasRole('ROLE_ECURIE') ? 'ecurie' : 'gite';

        $editUserForm = $this->createForm(AdminUserFormType::class, $user, ['context' => $context]);
        $editUserForm->handleRequest($request);


        if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {
            $brochureFile = $editUserForm->get('contractFileName')->getData();

            if ($brochureFile) {
                $brochureFileName = $fileUploadService->upload($brochureFile);
                $user->getCustomer()->setContractFileName($brochureFileName);
            }

            $uow = $entityManager->getUnitOfWork();
            $uow->computeChangeSets();
            // Récupérer les changements détectés par UnitOfWork
            $changes = $uow->getEntityChangeSet($user);

            //si isValid exist et si la valeur de isValid a changé à true et qu'il était initialement à false
            if(isset($changes['isValid']) && $changes['isValid'][0] === false && $changes['isValid'][1] === true) {

                $mailerService->send(
                    $user->getEmail(),
                    'Votre compte est à présent actif',
                    'mails/activationCompte.html.twig',
                    [
                        'firstName' => $user->getCustomer()->getFirstName(),
                        'lastName' => $user->getCustomer()->getLastName()
                    ]
                );
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Les informations de votre utilisateur ont bien été modifiées');

            return $this->redirectToRoute('app_admin_users_list');
        }

        return $this->render('admin/users/edit.html.twig', [
            'editUserForm' => $editUserForm->createView(),
        ]);

    }

    #[Route('/supprimer/{id}', name: 'user_remove')]
    public function remove(
        User $user,
        EntityManagerInterface $entityManager)
    {

        try {
            $entityManager->remove($user);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'suppression impossible : ' . $exception->getMessage());
        }

        return $this->redirectToRoute('app_admin_users_list');
    }
}
