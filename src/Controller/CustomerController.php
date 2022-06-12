<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerFilterType;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="customer_index", methods={"GET","POST"})
     */
    public function index(
        CustomerRepository $customerRepository,
        Request $request
        ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        $form = $this->createForm(CustomerFilterType::class);
        $form->handleRequest($request);

        $customers = $customerRepository->findByIdDesc($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $filterData = $form->get('type')->getData();

            if ('1' == $filterData) {
                $customers = $customerRepository->findByIdDesc($user);
            } elseif ('2' == $filterData) {
                $customers = $customerRepository->findByIdAsc($user);
            } elseif ('3' == $filterData) { 
                $customers = $customerRepository->findByNameAsc($user);
            } elseif ('4' == $filterData) {
                $customers = $customerRepository->findByNameDesc($user);
            }
        }

        return $this->render('customer/index.html.twig', [
            'customers' => $customers,
            'form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CustomerRepository $customerRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();
        $customer = new Customer();
        $customer->setUser($user);

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->add($customer, true);

            $this->addFlash('success', 'Nouveau client créé !');

            return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->add($customer, true);

            $this->addFlash('success', 'Client mis à jour !');

            return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $customerRepository->remove($customer, true);
        }

        $this->addFlash('success', 'Client supprimé ! ');

        return $this->redirectToRoute('customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
