<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\AdminUserFormType;
use App\Form\ProfilFormType;
use App\Form\RegistrationAdminFormType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;
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
    #[Route('/liste', name: 'utilisateurs_liste')]
    public function liste(UserRepository $userRepository, Request $request){

    $pagination = $userRepository->paginationQuery($request->query->get('page', 1));

        return $this->render('admin/users/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/modifier/{id}', name: 'utilisateur_modifier')]
    #[IsGranted('ROLE_ADMIN')]
    public function modifier(
        User $user,
        EntityManagerInterface $entityManager,
        Request $request) {
        $editUserForm = $this->createForm(AdminUserFormType::class, $user, ['context' => 'ecurie']);

        $editUserForm->handleRequest($request);

        if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_utilisateurs_liste');
        }

        return $this->render('admin/users/edit.html.twig', [
            'editUserForm' => $editUserForm->createView(),
        ]);

    }
    #[Route('/supprimer/{id}', name: 'utilisateur_supprimer')]
    public function supprimer(
        User $user,
        EntityManagerInterface $entityManager) {

        try {
            $entityManager->remove($user);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'suppression impossible : ' .$exception->getMessage());
        }

        return $this->redirectToRoute('admin_utilisateurs_liste');
    }

    #[Route('/creer/ecurie', name: 'utilisateur_creer_ecurie')]
    #[Route('/creer/gite', name: 'utilisateur_creer_gite')]
    public function creer(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher) {

        $user = new User();
        $context = null;
        if($request->attributes->get('_route') === 'admin_utilisateur_creer_ecurie')
        {
            $context = 'ecurie';
        } else {
            $context = 'gite';
        }
       
        $createUserForm = $this->createForm(RegistrationAdminFormType::class, $user, ['context' => $context]);

        $createUserForm->handleRequest($request);

        if($createUserForm->isSubmitted() && $createUserForm->isValid()) {

            //récupère le mot de passe brut depuis le champ plainPassword ainsi que les roles
            $inputPassword = $createUserForm->get('plainPassword')->getData();
            $role = $createUserForm->get('roles')->getData();

            //utilisise le service de hachage
            $hashedPassword = $passwordHasher->hashPassword($user, plainPassword: $inputPassword);
            //met à jour l'objet User avec le MDP haché
            $user->setPassword($hashedPassword);
            $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_utilisateurs_liste');
        }

        return $this->render('admin/users/create.html.twig', [
            'createUserForm' => $createUserForm->createView()
        ]);

    }


}
