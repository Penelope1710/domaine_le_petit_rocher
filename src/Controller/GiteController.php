<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GiteController extends AbstractController
{
    #[Route('/gite', name: 'gite_home')]
    public function home(): Response
    {
        return $this->render('gite/home.html.twig');
    }
}
