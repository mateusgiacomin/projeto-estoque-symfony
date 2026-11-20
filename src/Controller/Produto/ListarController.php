<?php

namespace App\Controller\Produto;

use App\Repository\ProdutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListarController extends AbstractController
{
    public function __construct(
        private ProdutoRepository $produtoRepository,
    ) {
    }

    #[Route('/app/produtos', name: 'listar_produtos', methods: ['GET'])]
    public function listar(): Response
    {
        return $this->render('app/produto/listar.html.twig', [
            'produtos' => $this->produtoRepository->listarComCategoria(),
        ]);
    }
}