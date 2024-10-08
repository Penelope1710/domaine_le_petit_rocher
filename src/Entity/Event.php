<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    public const OPENED_STATUS = 'opened';
    public const CLOSED_STATUS = 'closed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez indiquer un nom pour l'évènement")]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Veuillez indiquer une date de début")]
    #[Assert\GreaterThanOrEqual(
        "today",
        message: "La date et l'heure de début doivent être ultérieures à la date actuelle."
    )]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "Veuillez indiquer une date limite")]
    #[Assert\LessThanOrEqual(
        propertyPath: "startDate",
        message: "La date limite de participation doit être antérieure à celle du début de l'évènement."
    )]
    private ?\DateTimeInterface $deadLine = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez indiquer une description")]
    private ?string $eventDetails = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventCustomer::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $eventCustomers;


    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;


    public function __construct()
    {
        $this->eventCustomers = new ArrayCollection();
        $this->status = self::OPENED_STATUS;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getDeadLine(): ?\DateTimeInterface
    {
        return $this->deadLine;
    }

    public function setDeadLine(\DateTimeInterface $deadLine): static
    {
        $this->deadLine = $deadLine;

        return $this;
    }


    public function getEventDetails(): ?string
    {
        return $this->eventDetails;
    }

    public function setEventDetails(string $eventDetails): static
    {
        $this->eventDetails = $eventDetails;

        return $this;
    }

    /**
     * @return Collection<int, EventCustomer>
     */
    public function getEventCustomers(): Collection
    {
        return $this->eventCustomers;
    }

    public function addEventCustomer(EventCustomer $eventCustomer): static
    {
        if (!$this->eventCustomers->contains($eventCustomer)) {
            $this->eventCustomers->add($eventCustomer);
            $eventCustomer->setEvent($this);
        }

        return $this;
    }

    public function removeEventCustomer(EventCustomer $eventCustomer): static
    {
        if ($this->eventCustomers->removeElement($eventCustomer)) {
            // set the owning side to null (unless already changed)
            if ($eventCustomer->getEvent() === $this) {
                $eventCustomer->setEvent(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function isUserSubscribed(User $user): bool
    {
        foreach ($this->eventCustomers as $eventCustomer) {
            if ($eventCustomer->getCustomer()->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    public function getUserSubscribe(User $user): EventCustomer | null
    {
        foreach ($this->eventCustomers as $eventCustomer) {
            if ($eventCustomer->getCustomer()->getUser() === $user) {
                return $eventCustomer;
            }
        }

        return null;
    }

}
