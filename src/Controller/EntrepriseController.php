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
    // Affichage liste des entreprises
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findBy([],["raisonSociale" => "ASC"])        ;

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]); 
    }

    #[Route('/entreprise/new', name:'new_entreprise')]
    #[Route('/entreprise/{id}/edit', name:'edit_entreprise')]
    // Ajout ou édition d'entreprise
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $entityManager): Response{

        // Si un objet entreprise n'est pas passé en argument on crée un nouveau objet entreprise
        if(!$entreprise){
            $entreprise = new Entreprise;
        }


        // Création du formulaire d'ajout ou d'édition
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
            // Si l'entreprise getId renvoi true alors l'entreprise existe donc on la modifie sinon on en crée une nouvelle
            'edit' => $entreprise->getId()
        ]);
    }


    // Supprimer une entreprise
    #[Route('/entreprise/{id}/delete', name:'delete_entreprise')]
    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager){
        
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');

}
    
    // Affichage des infos d'une entreprise
    #[Route('/entreprise/{id}', name:'show_entreprise')]
    public function show(Entreprise $entreprise): Response {

        // VarDumper::dump($entreprise->getEmployes());die;
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

}
