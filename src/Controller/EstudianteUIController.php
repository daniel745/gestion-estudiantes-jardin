<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/estudiantes')]
class EstudianteUIController extends AbstractController
{
    #[Route('/', name: 'app_estudiantes')]
    public function index(): Response
    {
        return $this->render('estudiantes/index_estudiantes.html.twig');
    }

    #[Route('/nuevo', name: 'app_estudiantes_nuevo')]
    public function nuevoEstudiante(): Response
    {
        return $this->render('estudiantes/nuevo_estudiante.html.twig');
    }

    #[Route('/cambiar/{id}', name: 'app_estudiantes_editar')]
    public function editarEstudiante(int $id): Response
    {
        return $this->render('estudiantes/editar_estudiante.html.twig', 
        [
            'id' => $id
        ]);
    }
}

?>
