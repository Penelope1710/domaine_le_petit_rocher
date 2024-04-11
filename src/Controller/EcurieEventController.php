<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Customer;
use App\Entity\Event;
use App\Entity\EventCustomer;
use App\Entity\User;
use App\Form\CreateEventFormType;
use App\Form\SearchFormType;
use App\Repository\CategoryRepository;
use App\Repository\CustomerRepository;
use App\Repository\EventCustomerRepository;
use App\Repository\EventRepository;
use App\Security\EventCustomerVoter;
use App\Security\EventVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/prive/ecurie/evenements')]
class EcurieEventController extends AbstractController
{

    #[Route('/liste', name: 'ecurieevenement_liste')]
    public function liste(
        Request $request,
        EventRepository $eventRepository,
        CategoryRepository $categoryRepository): Response
    {
        $currentDate = new \DateTime();
        $user = $this->getUser();

        //je récupère tous les évènements et catégories dans la BDD
        $events = $eventRepository->findBy([], ['startDate' => 'ASC']);
        $categories = $categoryRepository->findAll();

        //Récupérer le searchForm pour le filtre de recherche
        $data = new SearchData();
        $searchFormType = $this->createForm(SearchFormType::class, $data);

        $searchFormType->handleRequest($request);
//        dd($data);
        //j'envoie les données de la recherche
        if ($searchFormType->isSubmitted() && $searchFormType->isValid()) {
            $data = $searchFormType->getData();

        $events = $eventRepository->findSearch($data, $user);
        }

//        $pagination = $eventRepository->paginationQuery($request->query->get('page', 1));

        return $this->render('ecurie/prive/list_event.html.twig', [
            //je passe mes variables à la vue
            'events' => $events,
            'categories' => $categories,
            'currentDate' => $currentDate,
            'user' => $user,
            'searchFormType' => $searchFormType->createView(),
            //'pagination' => $pagination
        ]);
    }

    #[Route('/creation', name: 'ecurieevenement_creer')]
    public function creer(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $currentDate = new \DateTime();
        $createEventForm = $this->createForm(CreateEventFormType::class, $event);

        $createEventForm->handleRequest($request);

        if ($createEventForm->isSubmitted() && $createEventForm->isValid()) {

            $event->setCreatedBy($this->getUser());

            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'votre évènement a bien été créé!');

            return $this->redirectToRoute('ecurieevenement_liste');
        }
        return $this->render('ecurie/prive/create_event.html.twig', [
            'createEventForm' => $createEventForm->createView(),
            'currentDate' => $currentDate,
        ]);
    }

    #[Route('/details/{id}', name: 'ecurieevenement_details')]
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

    #[Route('/supprimer/{id}', name: 'ecurieevenement_supprimer')]
    #[IsGranted(EventVoter::DELETE, subject: 'event')]
    public function supprimer(
        Event $event,
        EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute('ecurieevenement_liste');
    }

    #[Route('/modifier/{id}', name: 'ecurieevenement_modifier')]
    #[IsGranted(EventVoter::EDIT, subject: 'event')]
    public function modifier(
        Event $event,
        Request $request,
        EntityManagerInterface $entityManager): Response
    {
        //je récupère mon createForm
        $editEventForm = $this->createForm(CreateEventFormType::class, $event);

        $editEventForm->handleRequest($request);

        if ($editEventForm->isSubmitted() && $editEventForm->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Votre évènement a bien été modifié');

            return $this->redirectToRoute('ecurieevenement_liste');
        }

        return $this->render('ecurie/prive/edit_event.html.twig', [
            'editEventForm' => $editEventForm->createView(),
        ]);
    }

    #[Route('/inscription/{id}', name: 'ecurieevenement_inscrire')]
    #[IsGranted(EventVoter::SUBSCRIBE, subject: 'event')]
    public function inscrire(
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

        return $this->redirectToRoute('ecurieevenement_liste');
    }

    #[Route('/desinscription/{id}', name: 'ecurieevenement_desinscrire')]
    #[IsGranted(EventCustomerVoter::UNSUBSCRIBE, subject: 'eventCustomer' )]
    public function desinscrire(
        EventCustomer $eventCustomer,
        EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($eventCustomer);
        $entityManager->flush();

        return $this->redirectToRoute('ecurieevenement_liste');
    }

}