<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, CategorieRepository $categorieRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_filter', methods: ['GET'])]
    public function filter(ArticleRepository $articleRepository, CategorieRepository $categorieRepository, ?int $id = null): Response
    {
        if ($id) {
            $articles = $articleRepository->findBy(['categorie' => $id]);
        } else {
            $articles = $articleRepository->findAll();
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categorieRepository->findAll(),
        ]);
    }




    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }



    #[Route('/show/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(CommentRepository $commentRepository, Article $article, Request $request, EntityManagerInterface $em): Response
    {
        
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setAutor($this->getUser());
        $comment->setDate(new \DateTime());
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            
            return $this->redirectToRoute('app_article_index');
        }
        
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/comment/{id}', name: 'app_article_comment', methods: ['GET'])]
    public function comment(ArticleRepository $articleRepository, CommentRepository $commentRepository, int $id): Response
    {
        $article = $articleRepository->find($id);
        $comment = $commentRepository->findBy(['article' => $article]);

        return $this->render('article/comments.html.twig', [
            'comments' => $comment,



        ]);
    }


}
