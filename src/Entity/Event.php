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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez indiquer un nom pour l'évènement")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today", message: "La date et l'heure de début doivent être ultérieures à la date actuelle.")]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThan(propertyPath: "startDate", message: "La date limite de participation doit être antérieure à celle du début de l'évènement.")]
    private ?\DateTimeInterface $deadLine = null;

    #[ORM\Column(length: 150)]
    private ?string $organizerName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez indiquer une description")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $eventDetails = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventCustomer::class, orphanRemoval: true)]
    private Collection $eventCustomer;

    #[ORM\ManyToMany(targetEntity: Customer::class, mappedBy: 'event')]
    private Collection $customer;

    #[ORM\ManyToOne(inversedBy: 'event')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'event')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventStatus $eventStatus = null;

    public function __construct()
    {
        $this->eventCustomer = new ArrayCollection();
        $this->customer = new ArrayCollection();
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

    public function getOrganizerName(): ?string
    {
        return $this->organizerName;
    }

    public function setOrganizerName(string $organizerName): static
    {
        $this->organizerName = $organizerName;

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
    public function getEventCustomer(): Collection
    {
        return $this->eventCustomer;
    }

    public function addEventCustomer(EventCustomer $eventCustomer): static
    {
        if (!$this->eventCustomer->contains($eventCustomer)) {
            $this->eventCustomer->add($eventCustomer);
            $eventCustomer->setEvent($this);
        }

        return $this;
    }

    public function removeEventCustomer(EventCustomer $eventCustomer): static
    {
        if ($this->eventCustomer->removeElement($eventCustomer)) {
            // set the owning side to null (unless already changed)
            if ($eventCustomer->getEvent() === $this) {
                $eventCustomer->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customer $customer): static
    {
        if (!$this->customer->contains($customer)) {
            $this->customer->add($customer);
            $customer->addEvent($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): static
    {
        if ($this->customer->removeElement($customer)) {
            $customer->removeEvent($this);
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

    public function getEventStatus(): ?EventStatus
    {
        return $this->eventStatus;
    }

    public function setEventStatus(?EventStatus $eventStatus): static
    {
        $this->eventStatus = $eventStatus;

        return $this;
    }
}
