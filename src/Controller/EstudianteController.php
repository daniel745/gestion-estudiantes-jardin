<?php

namespace App\Controller;

use App\Entity\Estudiante;
use App\Repository\EstudianteRepository;
use App\Repository\SalonRepository;
use App\Service\SalonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/estudiantes')]
class EstudianteController extends AbstractController
{
    #[Route('/obtener', methods: ['GET'])]
    public function index(EstudianteRepository $repo): JsonResponse
    {
        $estudiantes = $repo->findAll();

        if (empty($estudiantes)) {
            return $this->json(['message' => 'No hay estudiantes en la base de datos'], 404);
            }

        return $this->json($estudiantes);
    }


    #[Route('/crear', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$data || !isset($data['nombre'], $data['acudiente'], $data['edad'], $data['genero']))
        {
            return $this->json(['message' => '¡Datos Invalidos!'], 400);
        }

        $estudiante = new Estudiante();
        $estudiante->setFechaCreado(new \DateTime());
        $estudiante->setEstudiante($data);

        $em->persist($estudiante);
        $em->flush();

        return $this->json([
            'message' => '¡Estudiante creado exitosamente!',
            'estudiante' => $estudiante
        ]);
    }

    #[Route('/buscar', methods: ['GET'])]
    public function show(Request $request, EstudianteRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $qb = $em -> createQueryBuilder();
        $qb -> select('e')
            -> from('App:Estudiante', 'e');

        $id = $request->query->get('id');
        $nombre = $request->query->get('nombre');
        $acudiente = $request->query->get('acudiente');
        $edad = $request->query->get('edad');
        $genero = $request->query->get('genero');

        if ($id) $qb -> andWhere('e.id = :id') -> setParameter('id', $id);
        //Busqueda parcial con LIKE para nombre y acudiente
        if ($nombre) $qb -> andWhere('e.nombre LIKE :nombre') -> setParameter('nombre', "%{$nombre}%");
        if ($acudiente) $qb -> andWhere('e.acudiente LIKE :acudiente') -> setParameter('acudiente', "%{$acudiente}%");
        if ($edad) $qb -> andWhere('e.edad = :edad') -> setParameter('edad', $edad);
        if ($genero) $qb -> andWhere('e.genero = :genero') -> setParameter('genero', $genero);

        $estudiantes = $qb -> getQuery() -> getResult();

        if (empty($estudiantes)) {
            return $this->json(['message' => 'Estudiante no encontrado en la base de datos'], 404);
        }
        
        return $this->json($estudiantes);
    }

    #[Route('/editar/{id}', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, EstudianteRepository $repo): JsonResponse
    {
        $estudiante = $repo->find($id);


        if (!$estudiante) {
            return $this->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);


        if(!$data){
            return $this->json(['message' => '¡Datos Invalidos!'], 400);
        }

        $estudiante->setFechaActualizado(new \DateTime());
        $estudiante->setEstudiante($data);

        $em->flush();

        return $this->json(['message' => '¡Estudiante actualizado exitosamente!']);
    }

    #[Route('/eliminar/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, EstudianteRepository $repo): JsonResponse
    {
        $estudiante = $repo->find($id);

        if (!$estudiante) {
            return $this->json(['message' => 'Estudiante no encontrado'], 404);
        }

        $em->remove($estudiante);
        $em->flush();

        return $this->json(['message' => '¡Estudiante eliminado exitosamente!']);
    }




    #[Route('/asignar-salon/{estudianteId}/{salonId}', methods: ['POST'])]
    public function asignarSalon(
        int $estudianteId,
        int $salonId,
        EstudianteRepository $estudianteRepo,
        SalonRepository $salonRepo,
        SalonService $salonService
    ): JsonResponse {
        $estudiante = $estudianteRepo->find($estudianteId);
        $salon = $salonRepo->find($salonId);

        if (!$estudiante) {
            return $this->json(['message' => 'Estudiante no encontrado'], 404);
        }

        if (!$salon) {
            return $this->json(['message' => 'Salon no encontrado'], 404);
        }

        $mensaje = $salonService->asignarEstudiante($estudiante, $salon);

        return $this->json(['message' => $mensaje]);
    }


    #[Route('/remover-salon/{estudianteId}/{salonId}', methods: ['DELETE'])]
    public function removerSalon(
        int $estudianteId,
        int $salonId,
        EstudianteRepository $estudianteRepo,
        SalonRepository $salonRepo,
        SalonService $salonService
    ): JsonResponse {
        $estudiante = $estudianteRepo->find($estudianteId);
        $salon = $salonRepo->find($salonId);

        if (!$estudiante) {
            return $this->json(['message' => 'Estudiante no encontrado'], 404);
        }

        if (!$salon) {
            return $this->json(['message' => 'Salon no encontrado'], 404);
        }

        $message = $salonService->removerEstudiante($estudiante, $salon);

        return $this->json(['message' => $message]);
    }
}


