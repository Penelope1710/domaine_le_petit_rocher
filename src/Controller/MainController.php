<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]

    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/mentions-legales', name: 'legal_informations')]
    public function mentionsLegales(): Response
    {
        return $this->render('legal_informations.html.twig');
    }
}
