<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Form\Admin\UnavailabilityFormType;
use App\Form\ReservationFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationAdminController extends AbstractController
{
    #[Route('/admin/reservations', name: 'app_admin_reservations_list')]
    public function list(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy(['isUnavailable' => false]);

        return $this->render('admin/reservations/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/admin/reservation/modifier/{id}', name: 'app_admin_reservation_edit')]
    public function reservationEdit(
        Request $request,
        Reservation $reservation,
        EntityManagerInterface $entityManager)
    {
        $editReservationForm = $this->createForm(ReservationFormType::class, $reservation);
        $editReservationForm->handleRequest($request);

        if ($editReservationForm->isSubmitted() && $editReservationForm->isValid())
        {
            $entityManager->flush();

            $this->addFlash('success', 'La réservation a bien été modifiée');

            return $this->redirectToRoute('app_admin_reservations_list');
        }

        return $this->render('admin/reservations/edit_user_reservation.html.twig',[
            'editReservationForm' => $editReservationForm,
        ]);
    }

    #[Route('/admin/reservation/supprimer/{id}', name: 'app_admin_reservation_remove')]
    public function reservationRemove(Reservation $reservation, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'La réservation a bien été supprimée');

        return $this->redirectToRoute('app_admin_reservations_list');
    }

    #[Route('/admin/indisponibilité/liste', name: 'app_admin_unavailabilities_list')]
    public function unavailibilitiesList(Request $request, ReservationRepository $reservationRepository): Response
    {
        $unavailabilities = $reservationRepository->findBy(['isUnavailable' => true]);

        return $this->render('admin/reservations/unavailibilities_list.html.twig', [
            'unavailabilities' => $unavailabilities,
        ]);
    }

    #[Route('/admin/reservations/indisponibilite_dates', name: 'app_admin_reservations_unavailable_dates')]
    public function unavailable(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unavailability = new Reservation();

        $form = $this->createForm(UnavailabilityFormType::class, $unavailability);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $unavailability->setUnavailable(true);
            $entityManager->persist($unavailability);
            $entityManager->flush();

            $this->addFlash('success', "La date d'indisponibilité a bien été ajoutée");

            return $this->redirectToRoute('app_admin_unavailabilities_list');

        }

        return $this->render('admin/reservations/unavailable_dates.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/reservation/indisponibilite_date/modifier/{id}', name: 'app_admin_reservations_unavailable_date_edit')]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UnavailabilityFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            $this->addFlash('success', "La date d'indisponibilité a bien été modifiée");

            return $this->redirectToRoute('app_admin_unavailabilities_list');
        }

        return $this->render('admin/reservations/edit_unavailable_date.html.twig', [
            'editForm' => $form,
        ]);
    }


    #[Route('/admin/reservation/indisponibilite_date/supprimer/{id}', name: 'app_admin_reservations_unavailable_date_remove')]
    public function remove(Reservation $reservation, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', "La date d'indisponibilité a bien été supprimée");

        return $this->redirectToRoute('app_admin_unavailabilities_list');
    }
}
