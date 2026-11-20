<?php

namespace App\Controller\Produto;

use App\Entity\Produto;
use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoverController extends AbstractController
{
    public function __construct(
        private ProdutoRepository $produtoRepository,
    ) {
    }

    #[Route('/app/produtos/{id}/remover', name: 'remover_produto', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remover(Produto $produto, Request $request): Response
    {
        $token = $request->request->getString('_token');
        if (!$this->isCsrfTokenValid('remover_produto_'.$produto->getId(), $token)) {
            $this->addFlash('danger', 'Token inválido. Tente novamente.');

            return $this->redirectToRoute('listar_produtos');
        }

        $nome = $produto->getNome();
        $this->produtoRepository->remover($produto);

        $this->addFlash('success', "Produto \"{$nome}\" removido com sucesso!");

        return $this->redirectToRoute('listar_produtos');
    }
}