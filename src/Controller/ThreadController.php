<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Post;
use App\Entity\Thread;
use App\Form\PostType;
use App\Form\ThreadType;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\ThreadRepository;
use App\Util\TransformUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(
        ThreadRepository $threadRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $entity = new Thread();
        $form = $this->createForm(ThreadType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            return $this->redirectToRoute('app_thread_view', ['id' => $entity->getId()]);
        }
        if ($this->getUser() != null) {
            $threads = $threadRepository->findAll();
        } else {
            $threads = $threadRepository->findBy(['secure' => false]);
        }
        return $this->render('thread/index.html.twig', [
            'threads' => $threads,
            'form' => $form->createView()
        ]);
    }

    #[Route('/thread/add', name: 'app_thread_add')]
    #[Route('/thread/edit/{id}', name: 'app_thread_edit')]
    #[Route('/category/{categoryId}/thread/add', name: 'app_category_thread_add')]
    public function form(
        ThreadRepository $threadRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        int $categoryId = 0,
        int $id = null
    ): Response
    {
        if ($id) {
            $entity = $threadRepository->find($id);
            if (!$entity) {
                throw new NotFoundHttpException('Thread not found');
            }
        } else {
            $category = $categoryRepository->find($categoryId);
            $entity = (new Thread())
                ->setCategory($category);
        }
        $form = $this->createForm(ThreadType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();
            if ($form->get('saveAndReturn')->isClicked()) {
                if (null !== $entity->getCategory()) {
                    return $this->redirectToRoute('app_subcategory_list', ['id' => $entity->getCategory()->getId()]);
                }
                return $this->redirectToRoute('app_category_list');
            }
            return $this->redirectToRoute('app_thread_view', ['id' => $entity->getId()]);
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
        if ($thread->isSecure() && $this->getUser() == null) {
            $this->addFlash('warning', "You don't have access to view this thread");
            return $this->redirectToRoute('app_home');
        }
        $post = (new Post())->setThread($thread);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setContent(TransformUtil::findAndReplaceLinks($post->getContent()));
            $entityManager->persist($post);
            foreach ($post->getAttachments()->toArray() as $attachment) {
                $entityManager->persist($attachment);
                $attachment->addPost($post);
            }
            $entityManager->flush();
            $form = $this->createForm(PostType::class, (new Post()));
        }
        $posts = $postRepository->findBy(['thread' => $thread], ['createDate' => 'DESC']);
        return $this->render('thread/view.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
            'thread' => $thread
        ]);
    }
}
