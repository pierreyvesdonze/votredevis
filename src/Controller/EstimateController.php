<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Entity\EstimateLine;
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
        if(!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        
        $totalHt  = 0;
        $totalTva = 0;
        $totalTtc = 0;

        foreach ($estimate->getEstimateLine() as $key => $estimateLine) {
            $totalHt += $estimateLine->getPrice() * $estimateLine->getQuantity();
            
            $totalTva += $estimateLine->getQuantity() * $estimateLine->getPrice() * ($estimateLine->getTva() / 100);

            $totalTtc += $totalHt + $totalTva;
        }

        return $this->render('estimate/show.html.twig', [
            'estimate' => $estimate,
            'totalHt'  => $totalHt,
            'totalTva' => $totalTva,
            'totalTtc' => $totalTtc
        ]);
    }
}
