<?php

namespace App\Command;

use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

#[AsCommand(name: 'app:close-event')]
class CloseEventCommand extends Command
{
    public function __construct(private EventRepository $eventRepository, private EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('fermeture des évènements dont la date est dépassée');
        $events = $this->eventRepository->findAll();
        foreach ($events as $event) {
          if ($event->getDeadLine() < new \DateTime()) {
              $event->setStatus(Event::CLOSED_STATUS);
          }
       }

        $this->entityManager->flush();

        $output->writeln('le traitement a été réalisé avec succès');

        return Command::SUCCESS;
    }

}

