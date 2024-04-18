<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\AdminUserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/utilisateurs', name: 'admin_')]
class UserController extends AbstractController
{
    #[Route('/liste', name: 'utilisateurs_liste')]
    public function liste(UserRepository $userRepository, Request $request)
    {

        $pagination = $userRepository->paginationQuery($request->query->get('page', 1));

        return $this->render('admin/users/list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/modifier/{id}', name: 'utilisateur_modifier')]
    #[IsGranted('ROLE_ADMIN')]
    public function modifier(
        User                   $user,
        EntityManagerInterface $entityManager,
        Request                $request,
        MailerInterface        $mailer)
    {

        $editUserForm = $this->createForm(AdminUserFormType::class, $user);

        $editUserForm->handleRequest($request);

        if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {
            $uow = $entityManager->getUnitOfWork();
            $uow->computeChangeSets();
            $changes = $uow->getEntityChangeSet($user);
            dd($changes);

            $entityManager->persist($user);
            $entityManager->flush();



            $email = (new TemplatedEmail())
                ->from($this->getParameter('mail_from'))
                ->to($user->getEmail())
                ->subject('Votre compte est à présent actif')
                ->htmlTemplate('mails/activationCompte.html.twig')
                ->context([
                    'firstName' => $user->getCustomer()->getFirstName(),
                    'lastName' => $user->getCustomer()->getLastName()
                ]);
            $mailer->send($email);

            return $this->redirectToRoute('admin_utilisateurs_liste');
        }

        return $this->render('admin/users/edit.html.twig', [
            'editUserForm' => $editUserForm->createView(),
        ]);

    }

    #[Route('/supprimer/{id}', name: 'utilisateur_supprimer')]
    public function supprimer(
        User $user,
        EntityManagerInterface $entityManager)
    {

        try {
            $entityManager->remove($user);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('danger', 'suppression impossible : ' . $exception->getMessage());
        }

        return $this->redirectToRoute('admin_utilisateurs_liste');
    }
}
