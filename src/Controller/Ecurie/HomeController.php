<?php

namespace App\Controller\Ecurie;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/ecurie/presentation', name: 'app_ecurie_home_index')]
    public function index(): Response
    {
        return $this->render('ecurie/public/presentation.html.twig');
    }

    }
