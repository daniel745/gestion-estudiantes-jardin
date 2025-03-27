<?php

namespace App\Entity;

use App\Repository\SalonRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
class Salon implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $nombre_salon;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $fechaCreado = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $fechaActualizado = null;

    #[ORM\ManyToMany(targetEntity: Estudiante::class, mappedBy: "salones")]
    private Collection $estudiantes;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nombre_salon' => $this->nombre_salon,
            'fechaCreado' => $this->fechaCreado?->format('Y-m-d H:i:s'),
            'fechaActualizado' => $this->fechaActualizado?->format('Y-m-d H:i:s'),
        ];
    }

    public function setFechaCreado(\DateTimeInterface $fechaCreado): self
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    public function setFechaActualizado(\DateTimeInterface $fechaActualizado): self
    {
        $this->fechaActualizado = $fechaActualizado;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSalon(): string
    {
        return $this->nombre_salon;
    }

    public function setNombreSalon(string $nombre_salon): self
    {
        $this->nombre_salon = $nombre_salon;
        return $this;
    }

    public function getFechaCreado(): ?\DateTimeInterface
    {
        return $this->fechaCreado;
    }

    public function getFechaActualizado(): ?\DateTimeInterface
    {
        return $this->fechaActualizado;
    }


    public function __construct()
    {
        $this->estudiantes = new ArrayCollection();
        $this->fechaCreado = new \DateTime();
    }

        public function getEstudiantes(): Collection
    {
        return $this->estudiantes;
    }
}
