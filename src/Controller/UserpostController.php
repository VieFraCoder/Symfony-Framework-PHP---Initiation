<?php

namespace App\Controller;

use DateTime;
use App\Entity\Posting;
use App\Form\PostingType;
use App\Repository\PostingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserpostController extends AbstractController
{
    #[Route('/userpost', name: 'app_user_post')]
    public function index(): Response
    {
        return $this->render('userpost/index.html.twig', [
           
        ]);
    }
    #[Route('/newpost', name: 'app_user_posting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostingRepository $postingRepository): Response
    {
        $user = $this->getUser();
        $posting = new Posting();
        $form = $this->createForm(PostingType::class, $posting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // je récupére le fichies passé dans le form
            $image = $form->get('image')->getdata();
            // si il y a une image de chargée
            if ($image) {
                // je crée un nom unique pour cette image et je remet l'extension
                $img_file_name = uniqid() . '.' . $image->guessExtension();
                // enregistrer le fichier dans le dossier image 
                $image->move($this->getParameter('upload_dir'), $img_file_name);
                // je set l'object article
                $posting->setImage($img_file_name);
            } else {
                // si $image = null je set l'image par default
                $posting->setImage('defaultimg.jpg');
            }
            $date_jour = new DateTime();
            $posting->setExecutedAt($date_jour);
            $posting->setUser($user);
        //    $entityManager->persist($posting);
        //    $entityManager->flush();

            $postingRepository->add($posting);
            return $this->redirectToRoute('app_user_post', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('userpost/new.html.twig', [
            'posting' => $posting,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/', name: 'app_user_posting_show', methods: ['GET'])]
    public function show(Posting $posting): Response
    {
        return $this->render('userpost/show.html.twig', [
            'posting' => $posting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_posting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posting $posting, PostingRepository $postingRepository): Response
    {
        //on verifie si le poste connecte
        $user = $this->getUser();
        $postuser = $posting->getUser();
        if($user != $postuser){
            return $this->redirectToRoute('home');
        }
        $othername = $posting->getImage();
        $form = $this->createForm(PostingType::class, $posting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // je récupére le fichies passé dans le form
             $image = $form->get('image')->getdata();
             // si il y a une image de chargée
             if ($image) {
                 // je crée un nom unique pour cette image et je remet l'extension
                 $img_file_name = uniqid() . '.' . $image->guessExtension();
                 // enregistrer le fichier dans le dossier image 
                 $image->move($this->getParameter('upload_dir'), $img_file_name);
                 // je set l'object article
                 $posting->setImage($img_file_name);
             } else {
                 // si $image = null je set l'image par default
                 $posting->setImage($othername);
             }
            $postingRepository->add($posting);
            return $this->redirectToRoute('app_user_post', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('userpost/edit.html.twig', [
            'posting' => $posting,
            'form' => $form,
        ]);
    }

    #[Route('/deletepost/{id}', name: 'app_user_posting_delete', methods: ['POST'])]
    public function delete(Request $request, Posting $posting, PostingRepository $postingRepository): Response
    {
        //on verifie si le poste connecte
        $user = $this->getUser();
        $postuser = $posting->getUser();
        if($user != $postuser){
            return $this->redirectToRoute('home');
        }
        if ($this->isCsrfTokenValid('delete'.$posting->getId(), $request->request->get('_token'))) {
            $postingRepository->remove($posting);
        }

        return $this->redirectToRoute('app_user_post', [], Response::HTTP_SEE_OTHER);
    }
}
