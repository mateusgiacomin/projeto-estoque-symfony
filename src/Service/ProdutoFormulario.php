<?php

namespace App\Service;

use App\Entity\Produto;
use App\Repository\CategoriaRepository;
use Symfony\Component\HttpFoundation\Request;

class ProdutoFormulario
{
    public function __construct(
        private CategoriaRepository $categoriaRepository,
    ) {
    }

    public function preencher(Produto $produto, Request $request, bool $comQuantidade): array
    {
        $nome = trim($request->request->getString('nome'));
        $descricao = trim($request->request->getString('descricao'));
        $categoriaId = trim($request->request->getString('categoria'));
        $valor = str_replace(',', '.', trim($request->request->getString('valor')));
        $quantidade = trim($request->request->getString('quantidade'));

        $erros = [];

        if ($nome === '' || mb_strlen($nome) > 100) {
            $erros[] = 'Informe o nome com até 100 caracteres.';
        }

        $categoria = ctype_digit($categoriaId) ? $this->categoriaRepository->find((int) $categoriaId) : null;
        if ($categoria === null) {
            $erros[] = 'Selecione uma categoria válida.';
        }

        if (!is_numeric($valor) || (float) $valor < 0 || (float) $valor > 99999999.99) {
            $erros[] = 'Informe um valor entre 0 e 99999999,99.';
        }

        $quantidadeInt = null;
        if ($comQuantidade) {
            if (ctype_digit($quantidade) && (int) $quantidade <= 2147483647) {
                $quantidadeInt = (int) $quantidade;
            } else {
                $erros[] = 'Informe a quantidade como um número inteiro maior ou igual a zero.';
            }
        }

        if ($erros) {
            return $erros;
        }

        $produto->setNome($nome);
        $produto->setDescricao($descricao === '' ? null : $descricao);
        $produto->setCategoria($categoria);
        $produto->setValor(number_format((float) $valor, 2, '.', ''));

        if ($quantidadeInt !== null) {
            $produto->setQuantidade($quantidadeInt);
        }

        return [];
    }
}