<?php

namespace App\Controller\Admin;

use App\Entity\Posting;
use App\Form\PostingType;
use App\Repository\PostingRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/posting')]
class PostingController extends AbstractController
{
    private $postingRepository;
    public function __construct(PostingRepository $postingRepository)
    {
       $this->postingRepository =  $postingRepository;
    }

    #[Route('/', name: 'app_posting_index', methods: ['GET'])]
    public function index(PostingRepository $postingRepository): Response
    {
        return $this->render('posting/index.html.twig', [
            'postings' => $this->$postingRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_posting_new', methods: ['GET', 'POST'])]
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
            return $this->redirectToRoute('app_posting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('posting/new.html.twig', [
            'posting' => $posting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_posting_show', methods: ['GET'])]
    public function show(Posting $posting): Response
    {
        return $this->render('posting/show.html.twig', [
            'posting' => $posting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_posting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posting $posting, PostingRepository $postingRepository): Response
    {
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
            return $this->redirectToRoute('app_posting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('posting/edit.html.twig', [
            'posting' => $posting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_posting_delete', methods: ['POST'])]
    public function delete(Request $request, Posting $posting, PostingRepository $postingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$posting->getId(), $request->request->get('_token'))) {
            $postingRepository->remove($posting);
        }

        return $this->redirectToRoute('app_posting_index', [], Response::HTTP_SEE_OTHER);
    }
}
