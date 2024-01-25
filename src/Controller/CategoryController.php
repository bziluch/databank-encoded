<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ThreadRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractAppController
{
    protected function getEntityClass():   string { return Category::class; }
    protected function getFormTypeClass(): string { return CategoryType::class; }
    protected function getIndexView():     string { return 'category/list.html.twig'; }
    protected function getFormView():      string { return 'category/form.html.twig'; }

    #[Route('/category/list/', name: 'app_category_list')]
    #[Route('/category/list/{id}', name: 'app_subcategory_list')]
    public function list(
        ThreadRepository $threadRepository,
        int $id = 0
    ): Response
    {
        $category = $this->getEntityRepository()->find($id);
        return $this->render($this->getIndexView(), [
            'parentCategory' => $category,
            'categories' => $this->getEntityRepository()->findBy(['parent' => $category]),
            'threads' => $threadRepository->findBy(['category' => $category])
        ]);
    }

    #[Route('/category/new/{id}', name: 'app_category_new')]
    #[Route('/category/new/', name: 'app_subcategory_new')]
    public function new(Request $request, int $id = 0) : Response
    {
        $entity = (new Category())
            ->setParent($this->getEntityRepository()->find($id));
        $form = $this->createForm($this->getFormTypeClass(), $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            if ($id > 0) {
                return $this->redirectToRoute('app_subcategory_list', ['id' => $entity->getId()]);
            } else {
                return $this->redirectToRoute('app_category_list');
            }
        }
        return $this->render($this->getFormView(), [
            'form' => $form,
            'entity' => $entity
        ]);
    }

    #[Route('/category/edit/{id}', name: 'app_category_edit')]
    public function form(Request $request, ?int $id = null): Response
    {
        return parent::form($request, $id);
    }

}