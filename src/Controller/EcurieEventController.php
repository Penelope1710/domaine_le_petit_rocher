<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Event;
use App\Form\CreateEventFormType;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile/ecurie/evenements')]
class EcurieEventController extends AbstractController
{
    #[Route('/affichage', name: 'ecurieevent_show')]
    public function show(
        EntityManagerInterface $entityManager,
        EventRepository $eventRepository,
        CategoryRepository $categoryRepository, int $id): Response
    {
        //je récupère tous les évènement et catégories dans la BDD
        $events = $eventRepository->findBy([], ['startDate' => 'ASC']);
        $categories = $categoryRepository->find($id);

        return $this->render('ecurie/show_event.html.twig', [
            //je passe mes variables à la vue
            'event' => $events,
            'category' => $categories
        ]);
    }
    #[Route('/creation', name: 'ecurieevent_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();

        $createEventForm = $this->createForm(CreateEventFormType::class, $event);

        $createEventForm->handleRequest($request);
        dump($event);
        if ($createEventForm->isSubmitted() && $createEventForm->isValid()) {

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('ecurieevent_show');
        }
        return $this->render('ecurie/create_event.html.twig', [
            'createEventForm' => $createEventForm->createView(),
            'Event' => $event
        ]);
    }

    #[Route('/details/{id}', name:'ecurieevent_details')]
    public function details(int $id, EventRepository $eventRepository): Response
    {
        //je récupère le détail d'un évènement en BDD
        $event = $eventRepository->find($id);

        return $this->render('ecurie/details_event.html.twig', [
            'event' => $event
        ]);

    }

}