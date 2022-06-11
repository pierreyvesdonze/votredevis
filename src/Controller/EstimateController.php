<?php

namespace App\Controller;

use App\Entity\Estimate;
use App\Entity\EstimateLine;
use App\Form\EstimateFilterType;
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
        EstimateRepository $estimateRepository,
        Request $request
    ): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $form = $this->createForm(EstimateFilterType::class);
        $form->handleRequest($request);

        $estimates = $estimateRepository->findByDateDesc($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $filterData = $form->get('type')->getData();

            if ('1' == $filterData) {
                $estimates = $estimateRepository->findByDateDesc($user);
            } elseif ('2' == $filterData) {
                $estimates = $estimateRepository->findByDateAsc($user);
            }

        }


        return $this->render('estimate/index.html.twig', [
            'form' => $form->createView(),
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
        $pdfOptions->set(['defaultFont', 'Arial', 'isRemoteEnabled' => true]);

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
        $dompdf->stream("Devis-".$estimate->getTitle().".pdf", [
            "Attachment" => true
        ]);
    }

    #[Route('/create', name: 'estimate_create')]
    public function create(
        Request $request
        )
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();
 
        $form = $this->createForm(EstimateType::class);
        $form->handleRequest($request);

        $estimate = new Estimate();
        $estimate->setUser($user);

        if ($form->isSubmitted() && $form->isValid()) {        

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
            $this->em->flush();

            $this->addFlash('success', 'Nouveau devis créé !');
            return $this->redirectToRoute('estimates');
        }

        return $this->render('estimate/create.html.twig', [
            'form' => $form->createView(),
            'estimate' => $estimate
        ]);
    }

    #[Route('/edit/{id}', name: 'estimate_edit')]
    public function edit(
        Request $request,
        Estimate $estimate
        )
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $estimateLines = $estimate->getEstimateLine();

            $estimate->setDate($form->get('date')->getData());
            $estimate->setTitle($form->get('title')->getData());
            $estimate->setCustomer($form->get('customer')->getData());

            foreach ($estimateLines as $estimateLine) {

                $estimateLine->setDescription($estimateLine->getDescription());
                $estimateLine->setDate($estimateLine->getdate());
                $estimateLine->setQuantity($estimateLine->getQuantity());
                $estimateLine->setPrice($estimateLine->getPrice());
                $estimateLine->setTva($estimateLine->getTva());

                $estimate->addEstimateLine($estimateLine);
            }

            $this->em->persist($estimate);
            $this->em->flush();

            $this->addFlash('success', 'Devis mis à jour !');
            return $this->redirectToRoute('estimate_edit', [
                'id' => $estimate->getId()
            ]);
        }

        return $this->render('estimate/edit.html.twig', [
            'form' => $form->createView(),
            'estimate' => $estimate
        ]);
    }

    #[Route('/delete/{id}', name: 'estimate_delete')]
    public function delete(Estimate $estimate)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $this->em->remove($estimate);
        $this->em->flush();
        $this->addFlash('success', 'Le devis a bien été supprimé');

        return $this->redirectToRoute('estimates');
    }
}
