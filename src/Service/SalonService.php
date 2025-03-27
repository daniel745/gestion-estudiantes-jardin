<?php

namespace App\Service;

use App\Entity\Estudiante;
use App\Entity\Salon;
use Doctrine\ORM\EntityManagerInterface;

class SalonService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function asignarEstudiante(Estudiante $estudiante, Salon $salon): string
    {

        if ($estudiante->getSalones()->contains($salon)) {
            return 'El estudiante ya se encuentra inscrito en este salon.';
        }

        $estudiante->addSalon($salon);
        $salon->getEstudiantes()->add($estudiante);

        $this->em->persist($estudiante);
        $this->em->persist($salon);
        $this->em->flush();
        return 'Estudiante asignado al salon correctamente.';
    }

    public function removerEstudiante(Estudiante $estudiante, Salon $salon): string
    {
        if ($estudiante->getSalones()->contains($salon)) {
            $estudiante->removeSalon($salon);
            $salon->getEstudiantes()->removeElement($estudiante);

            $this->em->persist($estudiante);
            $this->em->persist($salon);
            $this->em->flush();
            return 'Estudiante removido del salon correctamente.';
        }

        else {
            return 'El estudiante no se encuentra inscrito en este salon.';
        }
    }

    public function contarEstudiantesPorSalon(): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('s.id, s.nombre_salon, COUNT(e.id) as totalEstudiantes')
            ->from(Salon::class, 's')
            ->leftJoin('s.estudiantes', 'e')
            ->groupBy('s.id');

        return $qb->getQuery()->getResult();
    }

    public function verEstudiantesPorSalon($idSalon): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('e.id, s.nombre_salon, e.nombre, e.edad, e.acudiente, e.genero')
            ->from(Salon::class, 's')
            ->leftJoin('s.estudiantes', 'e')
            ->where('s.id = :id')
            ->setParameter('id', $idSalon);

        $resultado = $qb->getQuery()->getResult();
        
        if (empty($resultado) || $resultado[0]['nombre'] === null) {
            return [
                'salon_id' => $idSalon,
                'nombre_salon' => $resultado[0]['nombre_salon'] ?? 'Salón no encontrado',
                'mensaje' => 'No hay estudiantes en este salón'
            ];
        }

        return $resultado;
    }
}


?>