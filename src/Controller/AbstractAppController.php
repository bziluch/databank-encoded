<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractAppController extends AbstractController
{
    abstract protected function getEntityClass() : string;
    abstract protected function getFormTypeClass() : string;
    abstract protected function getIndexView() : string;
    abstract protected function getFormView() : string;

    protected function routeToRedirectAfterFormSubmit($entity) : ?RedirectResponse
    {
        return null;
    }

    protected function getEntityRepository() : EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntityClass());
    }

    protected function indexQuery() : array {
        return $this->getEntityRepository()->findAll();
    }

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function index() : Response
    {
        $entities = $this->indexQuery();
        return $this->render($this->getIndexView(), [
            'entities' => $entities
        ]);
    }

    public function form(Request $request, ?int $id = null) : Response
    {
        if ($id) {
            $entity = $this->entityManager->getRepository($this->getEntityClass())->find($id);
            if (!$entity) {
                throw new NotFoundHttpException();
            }
        } else {
            $entity = new ($this->getEntityClass())();
        }
        $form = $this->createForm($this->getFormTypeClass(), $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $redirectResponse = $this->routeToRedirectAfterFormSubmit($entity);
            if ($redirectResponse) {
                return $redirectResponse;
            }
        }
        return $this->render($this->getFormView(), [
            'entity' => $entity,
            'form' => $form->createView()
        ]);
    }
}