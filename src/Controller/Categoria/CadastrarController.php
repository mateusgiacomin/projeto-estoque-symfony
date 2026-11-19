<?php

namespace App\Controller\Categoria;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CadastrarController extends AbstractController
{
    public function __construct(
        private CategoriaRepository $categoriaRepository,
    ) {}

    #[Route('/app/categorias/cadastrar', name: 'cadastrar_categoria_show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('app/categoria/cadastrar.html.twig');
    }

    #[Route('/app/categorias', name: 'cadastrar_categoria_salvar', methods: ['POST'])]
    public function salvar(Request $request): Response
    {
        $nome = trim($request->request->getString('nome'));

        if (mb_strlen($nome) < 3 || mb_strlen($nome) > 50) {
            $this->addFlash('danger', 'O nome deve ter entre 3 e 50 caracteres.');

            return $this->redirectToRoute('cadastrar_categoria_show');
        }

        if ($this->categoriaRepository->findOneBy(['nome' => $nome])) {
            $this->addFlash('danger', "A categoria \"{$nome}\" já existe.");

            return $this->redirectToRoute('cadastrar_categoria_show');
        }

        $categoria = new Categoria();
        $categoria->setNome($nome);
        $this->categoriaRepository->salvar($categoria);

        $this->addFlash('success', "Categoria \"{$nome}\" cadastrada com sucesso!");

        return $this->redirectToRoute('listar_categorias');
    }
}