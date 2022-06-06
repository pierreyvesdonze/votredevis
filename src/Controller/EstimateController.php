<?php

namespace App\Controller;

use App\Repository\EstimateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstimateController extends AbstractController
{
    #[Route('/estimates', name: 'estimates')]
    public function index(
        EstimateRepository $estimateRepository
    ): Response
    {
        $estimates = $estimateRepository->findAll();

        return $this->render('estimate/index.html.twig', [
            'estimates' => $estimates,
        ]);
    }
}
