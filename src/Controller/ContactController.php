<?php

namespace App\Controller;

use App\Form\ContactType;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact_index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {


        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = $contactForm->getData();

            //Email
            $email = (new Email())
                ->from($this->getParameter('mail_from'))
                ->to($this->getParameter('mail_to'))
                ->subject('Message reçu du formulaire contact')
                ->html(
                    'Vous avez reçu un message de : ' . $contact['fullName'] . ' (' . $contact['email'] . ')<br>' .
                    $contact['message']
                )
            ;

            try {
            $mailer->send($email);
            $this->addFlash(
                'success',
                'Message envoyé !'
            );
            } catch (Exception $e) {
                dd($e);
            }

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}

