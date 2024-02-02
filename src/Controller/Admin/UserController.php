<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\ProfilFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/admin/utilisateurs', name: 'admin_')]
class UserController extends AbstractController
{
    #[Route('/list', name: 'user_list')]
    public function list(UserRepository $userRepository) {
        //TODO ajouter la pagination
        $users = $userRepository->findAll();

        return $this->render('admin/users/list.html.twig', [
           'users'=>$users
        ]);
    }

    #[Route('/modifier/{id}', name: 'user_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request) {

        $editUserForm = $this->createForm(ProfilFormType::class, $user);

        $editUserForm->handleRequest($request);

        if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/users/edit.html.twig', [
            'editUserForm' => $editUserForm->createView(),
        ]);

    }
    /*#[Route('/supprimer/{id}', name: 'user_remove')]
    public function remove(
        User $user,
        EntityManagerInterface $entityManager) {

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_user_list');
    }*/

    #[Route('/creer', name: 'user_create')]
    public function create(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher) {

        $user = new User();

        $createUserForm = $this->createForm(RegistrationFormType::class, $user);

        $createUserForm->handleRequest($request);

        if($createUserForm->isSubmitted() && $createUserForm->isValid()) {

        $inputPassword = $createUserForm->get('plainPassword')->getData();
            $role = $createUserForm->get('roles')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, plainPassword: $inputPassword);
            $user->setPassword($hashedPassword);
            $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/users/create.html.twig', [
            'createUserForm' => $createUserForm->createView()
        ]);

    }



}
