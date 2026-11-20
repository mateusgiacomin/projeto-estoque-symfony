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

class CadastrarController extends AbstractController
{
    public function __construct(
        private ProdutoRepository $produtoRepository,
        private CategoriaRepository $categoriaRepository,
        private ProdutoFormulario $formulario,
    ) {
    }

    #[Route('/app/produtos/cadastrar', name: 'cadastrar_produto_show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('app/produto/cadastrar.html.twig', [
            'categorias' => $this->categoriaRepository->findBy([], ['nome' => 'ASC']),
        ]);
    }

    #[Route('/app/produtos', name: 'cadastrar_produto_salvar', methods: ['POST'])]
    public function salvar(Request $request): Response
    {
        $produto = new Produto();
        $erros = $this->formulario->preencher($produto, $request, true);

        if ($erros) {
            return $this->render('app/produto/cadastrar.html.twig', [
                'categorias' => $this->categoriaRepository->findBy([], ['nome' => 'ASC']),
                'dados' => $request->request->all(),
                'erros' => $erros,
            ], new Response(status: Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $this->produtoRepository->salvar($produto);

        $this->addFlash('success', "Produto \"{$produto->getNome()}\" cadastrado com sucesso!");

        return $this->redirectToRoute('listar_produtos');
    }
}