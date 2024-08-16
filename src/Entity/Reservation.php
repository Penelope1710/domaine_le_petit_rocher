<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez indiquer une date d\'arrivée')]
//    #[Assert\GreaterThanOrEqual('today', message: 'La date et l\'heure de début doivent être ultérieures à la date actuelle')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'Veuillez indiquer une date de départ')]
//    #[Assert\GreaterThan(propertyPath: 'startDate', message: 'La date de fin doit être ultérieure à la date d\'arrivée')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $horseNb = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Customer $customer = null;

    #[ORM\Column]
    private ?bool $isUnavailable = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getHorseNb(): ?int
    {
        return $this->horseNb;
    }

    public function setHorseNb(?int $horseNb): static
    {
        $this->horseNb = $horseNb;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function isUnavailable(): ?bool
    {
        return $this->isUnavailable;
    }

    public function setUnavailable(bool $isUnavailable): static
    {
        $this->isUnavailable = $isUnavailable;

        return $this;
    }

}
