<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class GiteController extends AbstractController
{
    #[Route('/gite/presentation', name: 'gite_presentation')]
    public function presentation(): Response
    {
        return $this->render('gite/public/presentation.html.twig');
    }

    #[Route('/gite/disponibilites', name: 'gite_disponibilites')]
    public function disponibilites(): Response
    {
        $currentDate = new \DateTime();
        return $this->render('gite/public/availibility.html.twig', [
            'currentDate' => $currentDate
        ]);
    }

    #[Route('/gite/disponibilites/dates', name: 'gite_disponibilites_dates')]
    public function listeDispoDate(ReservationRepository $reservationRepository) {

        $reservations = $reservationRepository->findAllDate();
        $dates = [];


        //itération sur chaque réservation
        foreach ($reservations as $reservation) {
            $startDate = $reservation['startDate']->format('Y-m-d');
            $endDate = $reservation['endDate']->format('Y-m-d');

            /*$startDate = $reservation->getStartDate()->format('Y-m-d');
            $endDate = $reservation->getEndDate()->format('Y-m-d');*/

            //Ajout dans le tableau
            $dates[] = $startDate . ':' . $endDate;

        }

        return new JsonResponse (implode(", ", $dates));

    }

    #[Route('prive/gite/reservation', name: 'gite_reservation')]
    public function reservation (
        Request $request,
        EntityManagerInterface $entityManager) : Response
    {
        $currentDate = new \DateTime();

        $reservation = new Reservation();

        $reservationForm = $this->createForm(ReservationFormType::class, $reservation);

        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
//
            //Affecter la reservation au customer qui est l'utilisateur actuellement connecté
            $reservation->setCustomer(
                $this->getUser()->getCustomer()
            );
            $entityManager->persist($reservation);
            $entityManager->flush();

        }

        return $this->render('gite/prive/reservation.form.html.twig', [
            'reservationForm' => $reservationForm->createView(),
            'currentDate' => $currentDate,
        ]);
    }


    #[Route('/prive/gite/reservation/dates', name: 'gite_reservation_dates')]
    public function listeDate(ReservationRepository $reservationRepository) {

        $reservations = $reservationRepository->findAllDate();
        $dates = [];


        //itération sur chaque réservation
        foreach ($reservations as $reservation) {
            $startDate = $reservation['startDate']->format('Y-m-d');
            $endDate = $reservation['endDate']->format('Y-m-d');

            /*$startDate = $reservation->getStartDate()->format('Y-m-d');
            $endDate = $reservation->getEndDate()->format('Y-m-d');*/

            //Ajout dans le tableau
            $dates[] = $startDate . ':' . $endDate;

        }

        return new JsonResponse (implode(", ", $dates));

    }

}
