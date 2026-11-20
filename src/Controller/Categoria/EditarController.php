<?php

namespace App\Controller\Categoria;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditarController extends AbstractController
{
    public function __construct(
        private CategoriaRepository $categoriaRepository,
    ) {
    }

    #[Route('/app/categorias/{id}', name: 'editar_categoria_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Categoria $categoria): Response
    {
        return $this->render('app/categoria/editar.html.twig', [
            'categoria' => $categoria,
        ]);
    }

    #[Route('/app/categorias/{id}', name: 'editar_categoria_salvar', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function salvar(Categoria $categoria, Request $request): Response
    {
        $nome = trim($request->request->getString('nome'));

        if (mb_strlen($nome) < 3 || mb_strlen($nome) > 50) {
            $this->addFlash('danger', 'O nome deve ter entre 3 e 50 caracteres.');

            return $this->redirectToRoute('editar_categoria_show', ['id' => $categoria->getId()]);
        }

        $existente = $this->categoriaRepository->findOneBy(['nome' => $nome]);
        if ($existente && $existente->getId() !== $categoria->getId()) {
            $this->addFlash('danger', "A categoria \"{$nome}\" já existe.");

            return $this->redirectToRoute('editar_categoria_show', ['id' => $categoria->getId()]);
        }

        $categoria->setNome($nome);
        $this->categoriaRepository->salvar($categoria);

        $this->addFlash('success', "Categoria \"{$nome}\" atualizada com sucesso!");

        return $this->redirectToRoute('listar_categorias');
    }
}