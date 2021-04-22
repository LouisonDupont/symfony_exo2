<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/article", name="article")
     */
    public function index(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();


        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/new", name="new_article")
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $this ->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('article');

        }

        return $this->render(
            'article/new.html.twig',
            ['form' => $form->createView()]
        );
    }
}
