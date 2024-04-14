<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class EntrepriseController extends AbstractController
{
    // #[Route('/entreprise', name: 'app_entreprise')]
    // public function index(EntityManagerInterface $entityManager): Response
    // {
    //     $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();

    //     return $this->render('entreprise/index.html.twig', [
    //         'entreprises' => $entreprises
    //     ]);
    // }

    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findBy([],["raisonSociale" => "ASC"])        ;

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]); 
    }

    #[Route('/entreprise/new', name:'new_entreprise')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response{

        $entreprise = new Entreprise;

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entreprise = $form->getData();
            // Prepare PDO
            $entityManager->persist($entreprise);
            // Execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise');
        }

        return $this->render('entreprise/new.html.twig', [
            'formAddEntreprise' => $form,
        ]);
    }
    
    #[Route('/entreprise/{id}', name:'show_entreprise')]
    public function show(Entreprise $entreprise): Response {

        // VarDumper::dump($entreprise->getEmployes());die;
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

}
