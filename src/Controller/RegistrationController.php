<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register/ecurie', name: 'app_register_ecurie')]
    #[Route('/register/gite', name: 'app_register_gite')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        MailerInterface $mailer,
        EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        if($request->attributes->get('_route') === 'app_register_ecurie')
        {
            $context = 'ecurie';
        } else {
            $context = 'gite';
        }

        $form = $this->createForm(RegistrationFormType::class, $user, ['context' => $context] );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($context === 'gite'){
                $user->setRoles(['ROLE_GITE']);
            } else {
                $user->setRoles(['ROLE_ECURIE']);
            }
            $user->setIsValid(false);

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $mail = (new TemplatedEmail())
                ->from($this->getParameter('mail_from'))
                ->to($this->getParameter('mail_to'))
                ->subject('Vous avez une nouvelle demande de validation de compte')
                ->htmlTemplate('mails/registration.html.twig');

            $mailer->send($mail);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
