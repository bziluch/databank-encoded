<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Form\ThreadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
