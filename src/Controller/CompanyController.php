<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $companies = $entityManager
            ->getRepository(Company::class)
            ->findAll();
        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/company/new",name="company_new")
     */
    public function create( Request $request, EntityManagerInterface $entityManager)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class);
        $form->setData($company);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()){
            $company = $form->getData();

            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('company');
        }
        return $this->render('company/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
