<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salones')]
class SalonUIController extends AbstractController
{
    #[Route('/', name: 'app_salones')]
    public function index(): Response
    {
        return $this->render('salones/index_salones.html.twig');
    }

    #[Route('/nuevo', name: 'app_salon_nuevo')]
    public function nuevoSalon(): Response
    {
        return $this->render('salones/nuevo_salon.html.twig');
    }

    #[Route('/cambiar/{id}', name: 'app_salones_editar')]
    public function editarSalon(int $id): Response
    {
        return $this->render('salones/editar_salon.html.twig', 
        [
            'id' => $id
        ]);
    }

    #[Route('/salon-asignado', name: 'app_salon_asignado')]
    public function salonAsignado(): Response
    {
        return $this->render('salones/salon_asignado.html.twig');
    }
}

?>