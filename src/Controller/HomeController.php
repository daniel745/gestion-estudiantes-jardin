<?php 

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/index', name: 'homepage')]
    #[IsGranted('ROLE_USER')]
    public function homePage(): Response
    {
        return $this->render('index.html.twig');
    }
}
?>
