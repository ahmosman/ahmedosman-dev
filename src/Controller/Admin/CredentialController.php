<?php

namespace App\Controller\Admin;
use App\Controller\Abstract\AbstractTranslatableCrudController;
use App\Entity\Credential;
use App\Form\CredentialType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%app.supported_locales%>}/admin/credential')]
class CredentialController extends AbstractTranslatableCrudController
{
    private string $entityName;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        parent::__construct($requestStack, $entityManager);
        $this->entityName = 'Credential';
    }

    public function setTranslatableEntityFieldsFromForm($form)
    {
        $this->entityTranslation->setAuthor($form['author']->getData());
        $this->entityTranslation->setDescription($form['description']->getData());
    }

    public function createFormData(): array
    {
        return [
            'author' => $this->entityTranslation->getAuthor(),
            'description' => $this->entityTranslation->getDescription(),
        ];
    }

    #[Route('/new', name: 'credential_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->setTranslatableEntity(new Credential());

        $form = $this->createForm(CredentialType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);

            return $this->redirectToRoute('dashboard_credential', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_new.html.twig', [
            'form' => $form,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/edit', name: 'credential_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Credential $credential): Response
    {
        $this->setTranslatableEntity($credential);

        $form = $this->createForm(CredentialType::class, $this->createFormData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->setTranslatableFieldsAndFlushForm($form);

            return $this->redirectToRoute('dashboard_credential', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('CrudForm/_edit.html.twig', [
            'form' => $form,
            'entity' => $credential,
            'entity_name' => $this->entityName
        ]);
    }

    #[Route('/{id}/delete', name: 'credential_delete')]
    public function delete(Credential $credential): Response
    {
        $this->entityManager->remove($credential);
        $this->entityManager->flush();

        return $this->redirectToRoute('dashboard_credential', [], Response::HTTP_SEE_OTHER);
    }


}