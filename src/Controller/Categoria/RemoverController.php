<?php

namespace App\Controller\Categoria;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoverController extends AbstractController
{
    public function __construct(
        private CategoriaRepository $categoriaRepository,
    ) {
    }

    #[Route('/app/categorias/{id}/remover', name: 'remover_categoria', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remover(Categoria $categoria, Request $request): Response
    {
        $token = $request->request->getString('_token');
        if (!$this->isCsrfTokenValid('remover_categoria_'.$categoria->getId(), $token)) {
            $this->addFlash('danger', 'Token inválido. Tente novamente.');

            return $this->redirectToRoute('listar_categorias');
        }

        $nome = $categoria->getNome();
        $this->categoriaRepository->remover($categoria);

        $this->addFlash('success', "Categoria \"{$nome}\" removida com sucesso!");

        return $this->redirectToRoute('listar_categorias');
    }
}