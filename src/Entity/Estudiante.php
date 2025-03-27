<?php

namespace App\Entity;

use App\Repository\EstudianteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EstudianteRepository::class)]
class Estudiante implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private string $nombre;

    #[ORM\Column(length: 150)]
    private string $acudiente;

    #[ORM\Column]
    private int $edad;

    #[ORM\Column(length: 150)]
    private string $genero;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $fechaCreado = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $fechaActualizado = null;

    #[ORM\ManyToMany(targetEntity: Salon::class, inversedBy: "estudiantes")]
    #[ORM\JoinTable(name: "estudiante_salon")]
    private Collection $salones;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'acudiente' => $this->acudiente,
            'edad' => $this->edad,
            'genero' => $this->genero,
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

    public function setEstudiante(array $data): self
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;

    }


    public function __construct()
    {
        $this->salones = new ArrayCollection();
    }

    public function getSalones(): Collection
    {
        return $this->salones;
    }

    public function addSalon(Salon $salon): self
    {
        if (!$this->salones->contains($salon)) {
            $this->salones->add($salon);
        }
        return $this;
    }

    public function removeSalon(Salon $salon): self
    {
        $this->salones->removeElement($salon);
        return $this;
    }

}
