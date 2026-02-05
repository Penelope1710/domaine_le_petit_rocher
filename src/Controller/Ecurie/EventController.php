<?php

namespace App\Controller\Ecurie;

use App\Data\SearchData;
use App\Entity\Event;
use App\Entity\EventCustomer;
use App\Form\CreateEventFormType;
use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Security\EventCustomerVoter;
use App\Security\EventVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/prive/ecurie/evenements', name: 'app_')]
class EventController extends AbstractController
{

    #[Route('/liste', name: 'ecurie_event_index')]
    public function index(
        Request $request,
        EventRepository $eventRepository,
        CategoryRepository $categoryRepository): Response
    {
        $currentDate = new \DateTime();
        $user = $this->getUser();

        $categories = $categoryRepository->findAll();

        //Récupérer le searchForm pour le filtre de recherche
        $data = new SearchData();
        $searchFormType = $this->createForm(SearchFormType::class, $data);

        $searchFormType->handleRequest($request);
        //j'envoie les données de la recherche
        if ($searchFormType->isSubmitted() && $searchFormType->isValid()) {
            $data = $searchFormType->getData();
        }

        $events = $eventRepository->findSearch($data, $user, $request->query->get('page', 1));

        return $this->render('ecurie/prive/index.html.twig', [
            //je passe mes variables à la vue
            'events' => $events,
            'categories' => $categories,
            'currentDate' => $currentDate,
            'user' => $user,
            'searchFormType' => $searchFormType->createView(),
        ]);
    }

    #[Route('/creation', name: 'ecurie_event_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $currentDate = new \DateTime();
        $createEventForm = $this->createForm(CreateEventFormType::class, $event);

        $createEventForm->handleRequest($request);

        if ($createEventForm->isSubmitted() && $createEventForm->isValid()) {

            //j'inscris dans le champs createdBy le nom de l'utilisateur connecté, càd le mien
            $event->setCreatedBy($this->getUser());

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'votre évènement a bien été créé !');

            return $this->redirectToRoute('app_ecurie_event_index');
        }
        return $this->render('ecurie/prive/create_event.html.twig', [
            'createEventForm' => $createEventForm->createView(),
            'currentDate' => $currentDate,
        ]);
    }

    #[Route('/details/{id}', name: 'ecurie_event_details')]
    public function details(
        int $id,
        EventRepository $eventRepository,
        CategoryRepository $categoryRepository): Response
    {
        //je récupère le détail d'un évènement en BDD
        $event = $eventRepository->find($id);
        $category = $categoryRepository->findAll();

        return $this->render('ecurie/prive/details_event.html.twig', [
            'event' => $event,
            'category' => $category
        ]);
    }

    #[Route('/modifier/{id}', name: 'ecurie_event_edit')]
    #[IsGranted(EventVoter::EDIT, subject: 'event')]
    public function edit(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager): Response
    {
        //je récupère mon createForm
        $editEventForm = $this->createForm(CreateEventFormType::class, $event);

        $editEventForm->handleRequest($request);

        if ($editEventForm->isSubmitted() && $editEventForm->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Votre évènement a bien été modifié !');

            return $this->redirectToRoute('app_ecurie_event_index');
        }

        return $this->render('ecurie/prive/edit_event.html.twig', [
            'editEventForm' => $editEventForm->createView(),
        ]);
    }

    #[Route('/supprimer/{id}', name: 'ecurie_event_remove')]
    #[IsGranted(EventVoter::DELETE, subject: 'event')]
    public function remove(
        Event $event,
        EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success', 'votre évènement a bien été supprimé !');

        return $this->redirectToRoute('app_ecurie_event_index');
    }


    #[Route('/inscription/{id}', name: 'ecurie_event_subscribe')]
    #[IsGranted(EventVoter::SUBSCRIBE, subject: 'event')]
    public function subscribe(
        Event $event,
        EntityManagerInterface $entityManager): Response
    {
        //je récupère l'objet Customer associé à l'utilisateur connecté
        $customer = $this->getUser()->getCustomer();

        $eventCustomer = new EventCustomer();
        $eventCustomer->setEvent($event);
        $eventCustomer->setCustomer($customer);

        $event->addEventCustomer($eventCustomer);

        $entityManager->persist($eventCustomer);
        $entityManager->flush();

        $this->addFlash('success', 'votre inscription a bien été prise en compte !');

        return $this->redirectToRoute('app_ecurie_event_index');
    }

    #[Route('/desinscription/{id}', name: 'ecurie_event_unsubscribe')]
    #[IsGranted(EventCustomerVoter::UNSUBSCRIBE, subject: 'eventCustomer' )]
    public function unsubscribe(
        EventCustomer $eventCustomer,
        EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($eventCustomer);
        $entityManager->flush();

        $this->addFlash('success', 'votre désinscription a bien été prise en compte !');

        return $this->redirectToRoute('app_ecurie_event_index');
    }

}