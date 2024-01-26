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
    //#[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 150)]
    //#[Assert\NotBlank(message: "Le prénom ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: "Le prénom doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50, maxMessage: "Le numéro de téléphone ne peut pas dépasser 50 caractères.")]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    //#[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    #[Assert\Length(max: 255, maxMessage:"L'adresse ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $address = null;

    #[ORM\Column(length: 10)]
   // #[Assert\NotBlank(message: "Le code postal ne peut pas être vide")]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    //#[Assert\NotBlank(message: "La ville ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "La ville doit contenir au moins {{ limit }} carctères",
        maxMessage:"La ville ne peut pas dépasser {{ limit }} caractères.")]
    private ?string $city = null;

    #[ORM\Column]
    private ?\DateTime $birthDate = null;

    #[ORM\Column(length: 150)]
    //#[Assert\NotBlank(message: "Le nom de votre cheval ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: "Le nom de votre cheval doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom de votre cheval ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $horseName = null;

    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: EventCustomer::class, orphanRemoval: true)]
    private Collection $eventCustomer;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->eventCustomer = new ArrayCollection();
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
}
