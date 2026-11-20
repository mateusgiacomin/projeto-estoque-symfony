<?php

namespace App\Controller\Produto;

use App\Entity\Produto;
use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;
use App\Service\ProdutoFormulario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditarController extends AbstractController
{
    public function __construct(
        private ProdutoRepository $produtoRepository,
        private CategoriaRepository $categoriaRepository,
        private ProdutoFormulario $formulario,
    ) {
    }

    #[Route('/app/produtos/{id}', name: 'editar_produto_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Produto $produto): Response
    {
        return $this->render('app/produto/editar.html.twig', [
            'produto' => $produto,
            'categorias' => $this->categoriaRepository->findBy([], ['nome' => 'ASC']),
            'dados' => [
                'nome' => $produto->getNome(),
                'descricao' => $produto->getDescricao(),
                'categoria' => $produto->getCategoria()?->getId(),
                'valor' => $produto->getValor(),
            ],
        ]);
    }

    #[Route('/app/produtos/{id}', name: 'editar_produto_salvar', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function salvar(Produto $produto, Request $request): Response
    {
        $erros = $this->formulario->preencher($produto, $request, false);

        if ($erros) {
            return $this->render('app/produto/editar.html.twig', [
                'produto' => $produto,
                'categorias' => $this->categoriaRepository->findBy([], ['nome' => 'ASC']),
                'dados' => $request->request->all(),
                'erros' => $erros,
            ], new Response(status: Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $this->produtoRepository->salvar($produto);

        $this->addFlash('success', "Produto \"{$produto->getNome()}\" atualizado com sucesso!");

        return $this->redirectToRoute('listar_produtos');
    }
}