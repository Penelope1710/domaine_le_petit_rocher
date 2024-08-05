<?php

namespace App\Controller\Gite;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function App\Controller\implode;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/gite/presentation', name: 'app_gite_home_index')]
    public function index(): Response
    {
        return $this->render('gite/public/index.html.twig');
    }

    #[Route('/gite/disponibilites', name: 'app_gite_availabilities')]
    public function disponibilites(): Response
    {
        $currentDate = new \DateTime();
        return $this->render('gite/public/availibilities.html.twig', [
            'currentDate' => $currentDate
        ]);
    }

}
