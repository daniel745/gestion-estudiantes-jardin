<?php

namespace App\Controller;

use App\Entity\Salon;
use App\Repository\SalonRepository;
use App\Service\SalonService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salones')]
class SalonController extends AbstractController
{
    #[Route('/obtener', methods: ['GET'])]
    public function index(SalonRepository $repo): JsonResponse
    {
        $salones = $repo->findAll();

        if (empty($salones)) {
            return $this->json(['message' => 'No hay salones registrados en la base de datos'], 404);
            }


        return $this->json($salones);
    }


    #[Route('/crear', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if(!$data || !isset($data['nombre_salon']))
        {
            return $this->json(['message' => '¡Datos Invalidos!'], 400);
        }

        $salon = new Salon();
        $salon->setFechaCreado(new \DateTime());
        $salon->setNombreSalon($data['nombre_salon']);

        $em->persist($salon);
        $em->flush();

        return $this->json([
            'message' => '¡salon creado exitosamente!',
            'salon' => $salon
        ]);
    }

    #[Route('/buscar', methods: ['GET'])]
    public function show(Request $request, SalonRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $qb = $em -> createQueryBuilder();
        $qb -> select('e')
            -> from(Salon::class, 'e');

        $id = $request->query->get('id');
        $nombre_salon = $request->query->get('nombre_salon');

        if ($id) $qb -> andWhere('e.id = :id') -> setParameter('id', $id);
        //Busqueda parcial con LIKE para nombre del salon
        if ($nombre_salon) $qb -> andWhere('e.nombre_salon LIKE :nombre_salon') -> setParameter('nombre_salon', "%{$nombre_salon}%");

        $salones = $qb -> getQuery() -> getResult();

        if (empty($salones)) {
            return $this->json(['message' => 'Salon no encontrado en la base de datos'], 404);
        }
        
        return $this->json($salones);
    }

    #[Route('/editar/{id}', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request, SalonRepository $repo, EntityManagerInterface $em): JsonResponse
    {

        $salon = $repo->find($id);


        if (!$salon) {
            return $this->json(['message' => 'Salon no encontrado'], 404);
        }


        $data = json_decode($request->getContent(), true);

        if(!isset($data['nombre_salon'])){
            return $this->json(['message' => '¡Datos Invalidos!'], 400);
        }

        $salon->setFechaActualizado(new \DateTime());
        $salon->setNombreSalon($data['nombre_salon']);

        $em->flush();

        return $this->json(['message' => '¡salon actualizado exitosamente!']);
    }

    #[Route('/eliminar/{id}', methods: ['DELETE'])]
    public function delete(int $id, SalonRepository $repo, EntityManagerInterface $em): JsonResponse
    {

        $salon = $repo->find($id);

        if (!$salon) {
            return $this->json(['message' => 'Salon no encontrado'], 404);
        }

        $em->remove($salon);
        $em->flush();

        return $this->json(['message' => '¡Salon eliminado exitosamente!']);
    }


    #[Route('/reporte', methods: ['GET'])]
    public function obtenerReporte(SalonService $salonService): JsonResponse
    {
        $reporte = $salonService->contarEstudiantesPorSalon();

        return $this->json([
            'message' => 'Reporte generado exitosamente',
            'data' => $reporte
        ]);
    }

    #[Route('/estudiantePorSalon/{idSalon}', methods: ['GET'])]
    public function obtenerEstudiantePorSalon(SalonService $salonService, int $idSalon): JsonResponse
    {
        $informe = $salonService->verEstudiantesPorSalon($idSalon);

        return $this->json([
            'message' => 'Informe generado exitosamente',
            'data' => $informe
        ]);
    }
}
