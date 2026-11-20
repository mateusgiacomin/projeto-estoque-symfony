<?php

namespace App\Controller\Produto;

use App\Entity\Produto;
use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdicionarController extends AbstractController
{
    public function __construct(
        private ProdutoRepository $produtoRepository,
    ) {
    }

    #[Route('/app/produtos/{id}/adicionar', name: 'adicionar_produto', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function adicionar(Produto $produto, Request $request): Response
    {
        $token = $request->request->getString('_token');
        if (!$this->isCsrfTokenValid('adicionar_produto_'.$produto->getId(), $token)) {
            $this->addFlash('danger', 'Token inválido. Tente novamente.');

            return $this->redirectToRoute('listar_produtos');
        }

        $produto->setQuantidade($produto->getQuantidade() + 1);
        $this->produtoRepository->salvar($produto);

        $this->addFlash('success', "Adicionado 1 em \"{$produto->getNome()}\". Estoque atual: {$produto->getQuantidade()}.");

        return $this->redirectToRoute('listar_produtos');
    }
}