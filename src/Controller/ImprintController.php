<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImprintController extends AbstractController
{
    #[Route('/imprint', name: 'app_imprint', priority: 2)]
    public function index(): Response
    {
        return $this->render('imprint/index.html.twig', [
            'controller_name' => 'ImprintController',
        ]);
    }
}
