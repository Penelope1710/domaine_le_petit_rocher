<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use App\Form\Admin\AdminUserFormType;
use App\Form\InvoiceFormType;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
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
class InvoiceController extends AbstractController
{
    #[Route('/facture/liste_utilisateurs', name: 'invoice_users_list')]
    public function list(UserRepository $userRepository, Request $request): Response
    {

        $pagination = $userRepository->userecuriepaginationQuery($request->query->get('page', 1));

        return $this->render('admin/invoices/invoice_users_list.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/facture/creer/{id}', name: 'invoice_create')]
    public function create(
       EntityManagerInterface $entityManager,
       Customer               $customer,
       Request                $request,
       MailerInterface        $mailer): Response
    {

        $invoice = new Invoice();
        $currentDate = new \DateTime();

        $createInvoiceForm = $this->createForm(InvoiceFormType::class, $invoice);

        $createInvoiceForm->handleRequest($request);

        if ($createInvoiceForm->isSubmitted() && $createInvoiceForm->isValid())
        {
            /*$email = (new TemplatedEmail())
                ->from($this->getParameter('mail_from'))
                ->to($user->getEmail())
                ->subject('Votre compte est à présent actif')
                ->htmlTemplate('mails/activationCompte.html.twig')
                ->context([
                    'firstName' => $user->getCustomer()->getFirstName(),
                    'lastName' => $user->getCustomer()->getLastName()
                ]);
            $mailer->send($email);*/

            //j'associe le formulaire au customer
            $invoice->setCustomer($customer);

            $entityManager->persist($invoice);
            $entityManager->flush();

            $this->addFlash('success', 'Votre facture a bien été créée');

            return $this->redirectToRoute('app_admin_invoice_users_list');
        }
        return $this->render('admin/invoices/createInvoice.html.twig', [
            'currentDate' => $currentDate,
            'invoiceForm' => $createInvoiceForm->createView(),
            'customer' => $customer,
        ]);
    }
}
