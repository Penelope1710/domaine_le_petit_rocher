<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class GiteController extends AbstractController
{
    #[Route('/gite/presentation', name: 'gite_presentation')]
    public function presentation(): Response
    {
        return $this->render('gite/public/presentation.html.twig');
    }

    #[Route('/prive/gite/reservation', name: 'gite_reservation')]
    public function reservation (Request $request, EntityManagerInterface $entityManager) : Response
    {

        $reservation = new Reservation();

        $reservationForm = $this->createForm(ReservationFormType::class, $reservation);

        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
        }

        return $this->render('gite/prive/reservation.form.html.twig', [
            'reservationForm' => $reservationForm->createView()
        ]);
    }
}
