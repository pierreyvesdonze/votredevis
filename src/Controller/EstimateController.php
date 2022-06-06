<?php

namespace App\Controller;

use App\Entity\Estimate;
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
        $estimates = $estimateRepository->findBy([
            'user' => $this->getUser()
        ]);

        return $this->render('estimate/index.html.twig', [
            'estimates' => $estimates,
        ]);
    }

    #[Route('/estimate/{id}', name: 'estimate')]
    public function show(Estimate $estimate)
    {
        return $this->render('estimate/show.html.twig', [
            'estimate' => $estimate
        ]);
    }
}
