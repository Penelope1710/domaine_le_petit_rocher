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
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deadLine = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez indiquer une description")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "La description doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La description ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $eventDetails = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventCustomer::class, cascade: ["persist"], orphanRemoval: true)]
    private Collection $eventCustomer;


    #[ORM\ManyToOne(inversedBy: 'event')]
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
        $this->eventCustomer = new ArrayCollection();
        $this->customer = new ArrayCollection();
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
        foreach ($this->eventCustomer as $eventCustomer) {
            if ($eventCustomer->getCustomer()->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

}
