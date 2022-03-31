<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Posting;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostingRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostingRepository $postingRepository,UserRepository $userRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'list_posting' =>$postingRepository->findAllDesc(),
            'nbUser' => $userRepository->countUser(),
        ]);
    }
    // #[Route('/detailpost/{id}', name: 'show_detail_post', methods: ['GET'])]
    // public function show(Posting $posting): Response
    // {
    //     return $this->render('home/show.html.twig', [
    //         'posting' => $posting,
    //     ]);
    // }
    #[Route('/detailpost/{id}', name: 'show_detail_post', methods: ['GET', 'POST'])]
    public function new(Request $request,$id, CommentRepository $commentRepository, PostingRepository $postingRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $date_jour = new DateTime();
            // $comment->setExecutedAt($date_jour);
            $user = $this->getUser();
            $comment->setUser($user);
            $posting = $postingRepository->find($id);
            $comment->setPost($posting);
            $commentRepository->add($comment);
            return $this->redirectToRoute('show_detail_post', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/show.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'posting' => $postingRepository->find($id),
            
        ]);
    }
    
}
