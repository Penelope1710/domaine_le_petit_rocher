<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    #[Route('/compte/facture/impression/{id}', name: 'app_invoice')]
    public function index(UserRepository $userRepository, Request $request, MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $dompdf = new Dompdf();

        $html = $this->renderView('invoice/index.html.twig');

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('facture.pdf', [
            'attachment' => false
        ]);

        exit();
    }

    #[Route('/compte/facture/liste', name: 'app_invoice_list')]
    public function list(UserRepository $userRepository, Request $request)
    {
        //TODO récupérer liste des factures de l'utilisateur connecté
    }
}

