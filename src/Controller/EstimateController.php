<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Entity\EstimateLine;
use App\Form\EstimateType;
use App\Repository\EstimateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class EstimateController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/estimates', name: 'estimates')]
    public function index(
        EstimateRepository $estimateRepository
    ): Response {
        if (!$this->getUser()) {
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
        
        foreach ($estimate->getEstimateLine() as $estimateLine) {
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

    #[Route('/estimate/download/{id}', name: 'estimate_download')]
    public function download(Estimate $estimate)
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

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('estimate/download.pdf.html.twig', [
            'estimate' => $estimate,
            'totalHt'  => $totalHt,
            'totalTva' => $totalTva,
            'totalTtc' => $totalTtc
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("votredevis.pdf", [
            "Attachment" => true
        ]);
    }

    #[Route('/create', name: 'estimate_create')]
    public function create(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $form = $this->createForm(EstimateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $estimate = new Estimate();
            $estimate->setUser($user);

            $dataEstimateLines = $form->get('estimate_line')->getData();

            $estimate->setDate($form->get('date')->getData());
            $estimate->setTitle($form->get('title')->getData());
            $estimate->setCustomer($form->get('customer')->getData());
                        
            foreach ($dataEstimateLines as $estimateLine) {
                $newEstimateLine = new EstimateLine();

                $newEstimateLine->setDescription($estimateLine->getDescription());
                $newEstimateLine->setDate($estimateLine->getdate());
                $newEstimateLine->setQuantity($estimateLine->getQuantity());
                $newEstimateLine->setPrice($estimateLine->getPrice());
                $newEstimateLine->setTva($estimateLine->getTva());

                $estimate->addEstimateLine($newEstimateLine);

                $this->em->persist($estimateLine);
             
            }
            $this->em->persist($estimate);    
            dump($estimate);
            $this->em->flush();

            $this->addFlash('success', 'Nouveau devis créé !');
            return $this->redirectToRoute('estimates');
        }

        return $this->render('estimate/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
