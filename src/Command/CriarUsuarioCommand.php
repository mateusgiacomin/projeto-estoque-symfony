<?php

namespace App\Command;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:criar-usuario')]
class CriarUsuarioCommand
{
  public function __construct(
    private UsuarioRepository $usuarioRepository,
    private UserPasswordHasherInterface $hasher,
  ) {}

  public function __invoke(
    SymfonyStyle $io,
    #[Argument('Email')] string $email,
    #[Argument('Senha')] string $senha,
    #[Argument('Nome')] string $nome,
  ): int {
    $usuario = new Usuario();
    $usuario->setEmail($email);
    $usuario->setRoles(['ROLE_USER']);

    $hash = $this->hasher->hashPassword($usuario, $senha);
    $usuario->setPassword($hash);

    $this->usuarioRepository->salvar($usuario);
    $io->success('Usuário criado!');

    return Command::SUCCESS;
  }
}
