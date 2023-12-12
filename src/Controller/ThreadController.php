<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Thread;
use App\Form\PostType;
use App\Form\ThreadType;
use App\Repository\PostRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    #[Route('/thread/list', name: 'app_thread')]
    public function index(): Response
    {
        return $this->render('thread/index.html.twig', [
            'controller_name' => 'ThreadController',
        ]);
    }

    #[Route('/thread/form', name: 'app_thread_form')]
    public function form(EntityManagerInterface $entityManager, Request $request): Response
    {
        $entity = new Thread();
        $form = $this->createForm(ThreadType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->_onSave();
            $entityManager->persist($entity);
            $entityManager->flush();
        }
        return $this->render('thread/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/thread/view/{id}', name: 'app_thread_view')]
    public function view(
        ThreadRepository $threadRepository,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        int $id,
    ): Response
    {
        $thread = $threadRepository->find($id);
        if (!$thread) {
            throw new NotFoundHttpException('Thread not found!');
        }
        $post = (new Post())->setThread($thread);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($post);
            $entityManager->flush();
        }
        $posts = $postRepository->findBy([], ['createDate' => 'DESC']);
        return $this->render('thread/view.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts
        ]);
    }
}
