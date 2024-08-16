<?php

namespace App\Controller\Account;

use App\Entity\Customer;
use App\Entity\Reservation;
use App\Form\ProfilFormType;
use App\Form\RemoveAccountFormType;
use App\Form\ReservationFormType;
use App\Repository\ReservationRepository;
use App\Security\ReservationVoter;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/')]
class HomeController extends AbstractController
{

    #[Route('/compte/mon_compte', name: 'app_account_home_index')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/mon_compte/mon_contrat', name: 'app_account_home_download')]
    public function download(): BinaryFileResponse
    {
        $user = $this->getUser();
        //si le customer n'a pas de contrat
        if (!$user->getCustomer()->getContractFileName()) {
            throw new \Exception("Pas de contrat définit à ce nom");
        }

        $contractFileName = $user->getCustomer()->getContractFileName();

        //retourne le fichier avec son chemin d'accès et le nom du fichier concaténé
        return $this->file($this->getParameter('uploads_directory') . $contractFileName);
    }

    #[Route('/compte/mon_compte/mes_reservations', name: 'app_account_home_reservationList')]
    public function reservationList(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['customer' => $user->getCustomer()]);
        $currentDate = new \DateTime();

        return $this->render('gite/prive/reservation_list.html.twig', [
            'reservations' => $reservations,
            'currentDate' => $currentDate,
        ]);
    }

    #[Route('/compte/mon_compte/mes_reservations/modifier/{id}', name: 'app_account_home_reservationList_edit')]
    #[IsGranted('edit', 'reservation')]
    public function edit(
        Reservation $reservation,
        Request $request,
        EntityManagerInterface $entityManager,
        MailerService $mailerService) : Response
    {
        $currentDate = new \DateTime();
        $editReservationForm = $this->createForm(ReservationFormType::class, $reservation);
        $editReservationForm->handleRequest($request);

        if ($editReservationForm->isSubmitted() && $editReservationForm->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre réservation a bien été modifiée');

            $mailerService->send(
                $this->getParameter('mail_to'),
                'Vous avez une modification de réservation',
                'mails/reservations.html.twig',
                [
                    'dateStart'=> $reservation->getStartDate(),
                    'dateEnd'=> $reservation->getEndDate(),
                    'firstName'=> $reservation->getCustomer()->getFirstName(),
                    'lastName'=> $reservation->getCustomer()->getLastName()
                ]
            );

            return $this->redirectToRoute('app_account_home_reservationList');
        }

        return $this->render('gite/prive/reservation.form.html.twig', [
            'reservationForm' => $editReservationForm->createView(),
            'currentDate' => $currentDate,
        ]);

    }

    #[Route('/compte/mon_compte/mes_reservations/supprimer/{id}', name: 'app_account_home_reservationList_remove')]
    #[IsGranted('delete', 'reservation')]
    public function remove(
        Reservation $reservation,
        EntityManagerInterface $entityManager,
        MailerService $mailerService): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();

        $this->addFlash('success', 'Votre réservation a bien été supprimée');

        $mailerService->send(
            $this->getParameter('mail_to'),
            'Vous avez une annulation de réservation',
            'mails/delete_reservations.html.twig',
            [
                'dateStart'=> $reservation->getStartDate(),
                'dateEnd'=> $reservation->getEndDate(),
                'firstName'=> $reservation->getCustomer()->getFirstName(),
                'lastName'=> $reservation->getCustomer()->getLastName()
            ]
        );

        return $this->redirectToRoute('app_account_home_reservationList');
    }
}