<?php

namespace App\Controller\Gite;

use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use function App\Controller\implode;

#[Route('/')]
class ReservationController extends AbstractController
{
    #[Route('prive/gite/reservation', name: 'app_gite_reservation_create')]
    public function create (
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer) : Response
    {
        $currentDate = new \DateTime();

        $reservation = new Reservation();

        $reservationForm = $this->createForm(ReservationFormType::class, $reservation);

        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            //Affecter la reservation au customer qui est l'utilisateur actuellement connecté
            $reservation->setCustomer(
                $this->getUser()->getCustomer()
            );
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de réservation a bien été envoyée');

            $email = (new TemplatedEmail())
                ->from($this->getParameter('mail_from'))
                ->to($this->getParameter('mail_to'))
                ->subject('Vous avez une nouvelle demande de réservation')
                ->htmlTemplate('mails/reservations.html.twig')
                ->context([
                    'dateStart'=> $reservation->getStartDate(),
                    'dateEnd'=> $reservation->getEndDate(),
                    'firstName'=> $reservation->getCustomer()->getFirstName(),
                    'lastName'=> $reservation->getCustomer()->getLastName()
                ]);
            $mailer->send($email);

            return $this->redirectToRoute('app_gite_home_index');
        }

        return $this->render('gite/prive/reservation.form.html.twig', [
            'reservationForm' => $reservationForm->createView(),
            'currentDate' => $currentDate,
        ]);
    }


    #[Route('/gite/reservation/dates', name: 'app_gite_reservation_listdate')]
    public function listDate(ReservationRepository $reservationRepository): JsonResponse
    {
        $reservations = $reservationRepository->findAllDate();
        $dates = [];

        //itération sur chaque réservation
        foreach ($reservations as $reservation) {
            //startDate est un objet DateTime dans le tableau $reservation
            $startDate = $reservation['startDate']->format('Y-m-d');
            $endDate = $reservation['endDate']->format('Y-m-d');

            /*$startDate = $reservation->getStartDate()->format('Y-m-d');
            $endDate = $reservation->getEndDate()->format('Y-m-d');*/

            //Ajout dans le tableau
            $dates[] = $startDate . ':' . $endDate;

        }

        return new JsonResponse (\implode(", ", $dates));

    }

}
