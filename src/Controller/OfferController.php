<?php

namespace App\Controller;

use App\Entity\Applicant;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class OfferController extends AbstractController
{
    /**
     * @Route("/offers", name="offers")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offers = $entityManager->getRepository(Offer::class)->findAll();

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Offer $offer
     * @param EntityManagerInterface $entityManager
     * @return void
     * @Route("/offers/{id}/apply",name="offer_apply")
     * @IsGranted("ROLE_APPLICANT")
     */
    public function apply(Offer $offer, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $applicant = $entityManager->getRepository(Applicant::class)->findOneBy([
            'user' => $user,
        ]);
        if($applicant){
            $offer->addApplicant($applicant);
            $entityManager->persist($offer);
            try{
                $entityManager->flush();
                $this->addFlash('success', 'Solicitud recibida');
            }catch(\Exception $e){
                $this->addFlash('danger', 'La solicitud no pudo almacenarse. Por Favor intente nuevamente.');
            }

            return $this->redirectToRoute('offers');
        }
    }
}
