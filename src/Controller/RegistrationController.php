<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


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
                $user->setIsValid(true);

            } else {
                $user->setRoles(['ROLE_ECURIE']);
                $user->setIsValid(false);
                    $mail = (new TemplatedEmail())
                        ->from($this->getParameter('mail_from'))
                        ->to($this->getParameter('mail_to'))
                        ->subject('Vous avez une nouvelle demande de validation de compte')
                        ->htmlTemplate('mails/registration.html.twig')
                        ->context([
                        'firstName'=> $user->getCustomer()->getFirstName(),
                        'lastName'=> $user->getCustomer()->getLastName()
                    ]);

                    $mailer->send($mail);

                    $this->addFlash('success', 'votre compte a bien été créé, il sera activé au plus tard sous 24h!');
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            if($context === 'gite') {
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
