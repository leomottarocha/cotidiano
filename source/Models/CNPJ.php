<?php

declare(strict_types=1);

namespace Source\Models;

final class Cnpj
{
    private const TAMANHO = 14;

    /**
     * @var int[]
     */
    private const PESO_DV_1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * @var int[]
     */
    private const PESO_DV_2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    public function mascararCnpj(string $cnpj): string
    {
        $base = $this->normalizarCnpj($cnpj);

        if (!$this->temEstruturaBasicaCnpj($base)) {
            return $cnpj;
        }

        return sprintf(
            '%s.%s.%s/%s-%s',
            substr($base, 0, 2),
            substr($base, 2, 3),
            substr($base, 5, 3),
            substr($base, 8, 4),
            substr($base, 12, 2)
        );
    }

    public function validarCnpj(string $cnpj): bool
    {
        $base = $this->normalizarCnpj($cnpj);

        if (!$this->temEstruturaBasicaCnpj($base)) {
            return false;
        }

        if (!$this->temFormatoValidoCnpj($base)) {
            return false;
        }

        if ($this->cnpjNumerico($base) && preg_match('/^(\d)\1{13}$/', $base)) {
            return false;
        }

        $raiz = substr($base, 0, 12);
        $digitosInformados = substr($base, 12, 2);

        $digito1 = $this->calcularDigitoVerificadorCnpj($raiz, self::PESO_DV_1);
        $digito2 = $this->calcularDigitoVerificadorCnpj($raiz . $digito1, self::PESO_DV_2);

        return $digitosInformados === $digito1 . $digito2;
    }

    public function limparCnpj(string $cnpj): string
    {
        return $this->normalizarCnpj($cnpj);
    }

    public function cnpjNumerico(string $cnpj): bool
    {
        return preg_match('/^\d{14}$/', $this->normalizarCnpj($cnpj)) === 1;
    }

    public function cnpjAlfanumerico(string $cnpj): bool
    {
        $base = $this->normalizarCnpj($cnpj);

        return $this->temEstruturaBasicaCnpj($base)
            && preg_match('/^[A-Z0-9]{12}\d{2}$/', $base) === 1
            && preg_match('/[A-Z]/', substr($base, 0, 12)) === 1;
    }

    private function normalizarCnpj(string $cnpj): string
    {
        $cnpj = strtoupper(trim($cnpj));

        return preg_replace('/[^A-Z0-9]/', '', $cnpj) ?? '';
    }

    private function temEstruturaBasicaCnpj(string $cnpj): bool
    {
        return strlen($cnpj) === self::TAMANHO;
    }

    private function temFormatoValidoCnpj(string $cnpj): bool
    {
        return preg_match('/^[A-Z0-9]{12}\d{2}$/', $cnpj) === 1;
    }

    /**
     * @param int[] $pesos
     */
    private function calcularDigitoVerificadorCnpj(string $base, array $pesos): string
    {
        $soma = 0;

        foreach ($pesos as $indice => $peso) {
            $soma += $this->valorCaracterCnpj($base[$indice]) * $peso;
        }

        $resto = $soma % 11;
        $digito = ($resto < 2) ? 0 : 11 - $resto;

        return (string) $digito;
    }

    private function valorCaracterCnpj(string $caractere): int
    {
        return ord($caractere) - 48;
    }
}
