<?php

declare(strict_types=1);

namespace Source\Models;


final class Cpf
{
    private const TAMANHO = 11;

    public function mascararCpf(string $cpf): string
    {
        $base = $this->normalizarCpf($cpf);

        if (!$this->temEstruturaBasicaCpf($base)) {
            return $cpf;
        }

        return sprintf(
            '%s.%s.%s-%s',
            substr($base, 0, 3),
            substr($base, 3, 3),
            substr($base, 6, 3),
            substr($base, 9, 2)
        );
    }

    public function validarCpf(string $cpf): bool
    {
        $base = $this->normalizarCpf($cpf);

        if (!$this->temEstruturaBasicaCpf($base)) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $base)) {
            return false;
        }

        $digito1 = $this->calcularDigitoCpf(substr($base, 0, 9), 10);
        $digito2 = $this->calcularDigitoCpf(substr($base, 0, 9) . $digito1, 11);

        return $base === substr($base, 0, 9) . $digito1 . $digito2;
    }

    public function limparCpf(string $cpf): string
    {
        return $this->normalizarCpf($cpf);
    }

    private function normalizarCpf(string $cpf): string
    {
        return preg_replace('/\D+/', '', trim($cpf)) ?? '';
    }

    private function temEstruturaBasicaCpf(string $cpf): bool
    {
        return strlen($cpf) === self::TAMANHO;
    }

    private function calcularDigitoCpf(string $base, int $pesoInicial): string
    {
        $soma = 0;
        $peso = $pesoInicial;

        for ($i = 0; $i < strlen($base); $i++) {
            $soma += (int) $base[$i] * $peso;
            $peso--;
        }

        $digito = ((10 * $soma) % 11) % 10;

        return (string) $digito;
    }
}