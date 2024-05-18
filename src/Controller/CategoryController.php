<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractAppController
{
    protected function getEntityClass():   string { return Category::class; }
    protected function getFormTypeClass(): string { return CategoryType::class; }
    protected function getIndexView():     string { return 'category/list.html.twig'; }
    protected function getFormView():      string { return 'category/form.html.twig'; }

    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return parent::index();
    }

    #[Route('/category/new', name: 'app_category_new')]
    #[Route('/category/edit/{id}', name: 'app_category_edit')]
    public function form(Request $request, ?int $id = null): Response
    {
        return parent::form($request, $id);
    }

}