<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    private ?string $lastName = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide")]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide")]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    private ?string $address = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: "Le code postal ne peut pas être vide")]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide")]
    private ?string $city = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le date de naissance ne peut pas être vide")]
    private ?\DateTime $birthDate = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $horseName = null;

    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: EventCustomer::class, orphanRemoval: true)]
    private Collection $eventCustomer;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->eventCustomer = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }


    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTime $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getHorseName(): ?string
    {
        return $this->horseName;
    }

    public function setHorseName(string $horseName): static
    {
        $this->horseName = $horseName;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

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
            $eventCustomer->setCustomer($this);
        }

        return $this;
    }

    public function removeEventCustomer(EventCustomer $eventCustomer): static
    {
        if ($this->eventCustomer->removeElement($eventCustomer)) {
            // set the owning side to null (unless already changed)
            if ($eventCustomer->getCustomer() === $this) {
                $eventCustomer->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCustomer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCustomer() === $this) {
                $reservation->setCustomer(null);
            }
        }

        return $this;
    }
}
