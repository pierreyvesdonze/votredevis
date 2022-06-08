<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Entity\EstimateLine;
use App\Repository\EstimateLineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstimateLineController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @Route("/estimate/line/delete", name="estimate_line_delete", methods={"POST"}, options={"expose"=true})
     */
    public function index(
        Request $request,
        EstimateLineRepository $estimateLineRepository
        ): Response
    {
        if ($request->isMethod('POST')) {
            $estimateLineId = $request->getContent();
            $lineToDelete = $estimateLineRepository->findOneBy([
                'id' => $estimateLineId
            ]);

            $this->em->remove($lineToDelete);
            $this->em->flush();

            $this->addFlash('success', 'La ligne a bien été supprimée');
        }

        return $this->json([
            $lineToDelete->getId()
        ]);
    }
}
