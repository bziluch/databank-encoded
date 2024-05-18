<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Util\TransformUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/edit/{id}', name: 'app_post_edit')]
    public function edit(
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        int $id
    ): Response
    {
        $post = $postRepository->find($id);
        if (!$post) {
            throw new NotFoundHttpException('Thread not found!');
        }
        if ($post->getThread()->isSecure() && $this->getUser() == null) {
            $this->addFlash('warning', "You don't have access to view this thread");
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            // TODO: do edit date on entity listeners
            $post->setEditDate(new \DateTime('now'));
            $entityManager->persist($post);
            foreach ($post->getAttachments()->toArray() as $attachment) {
                $entityManager->persist($attachment);
                $attachment->addPost($post);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Post edited successfully!');
            return $this->redirectToRoute('app_thread_view', ['id' => $post->getThread()->getId()]);
        }
        return $this->render('post/form.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }

}
