<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Services\MailerService;
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
        MailerService $mailerService,
        EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        //je récupère la route qui a été appelée grace à la propriété attributes, si celle-ci est écurie alors nous utilisons le context ecurie
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
                $mailerService->send(
                    $this->getParameter('mail_to'),
                    'GÎTE: Vous avez une nouvelle création de compte',
                    'mails/gite_registration.html.twig',
                    [
                        'firstName'=> $user->getCustomer()->getFirstName(),
                        'lastName'=> $user->getCustomer()->getLastName()
                    ]
                );

                $this->addFlash('success', 'votre compte a bien été créé, vous pouvez dès à présent vous connecter!');

            } else {
                $user->setRoles(['ROLE_ECURIE']);
                $user->setIsValid(false);
                $mailerService->send(
                    $this->getParameter('mail_to'),
                    'Vous avez une nouvelle demande de validation de compte',
                    'mails/ecurie_registration.html.twig',
                    [
                        'firstName'=> $user->getCustomer()->getFirstName(),
                        'lastName'=> $user->getCustomer()->getLastName()
                    ]
                );

                    $this->addFlash(
                        'success',
                        'votre compte a bien été créé, vous revevrez un e-mail sous 24h lorsque celui sera actif!'
                    );
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


            return $this->redirectToRoute('main_home');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
