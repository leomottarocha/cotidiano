<?php

namespace Source\Models;

use \DateTime;
use \DateInterval;
use \Exception;
use \DateTimeZone;
use \PDOException;

class Cotidiano
{
    public function contarDias($dataInicio, $dataTermino, $timeZone = 'America/Sao_Paulo')
    {
        try {
            $timeZone       = new DateTimeZone($timeZone);
            $dataInicio     = new DateTime($dataInicio, $timeZone);
            $dataTermino    = new DateTime($dataTermino, $timeZone);

            $intervalo = $dataInicio->diff($dataTermino);
            $totalDias    = ($intervalo->invert == 1) ? -abs($intervalo->days) : $intervalo->days;
            return $totalDias;
        } catch (PDOException $exception) {
            var_dump($exception->getMessage());
        }
    }

    public function letrasMinusculas(array $dados)
    {
        return array_unique(array_map('mb_strtolower', $dados));
    }

    public function retornarDiaDaSemana($data)
    {
        // Converte a data para o formato de timestamp
        $timestamp = strtotime($data);

        // Verifica se a conversão foi bem-sucedida
        if ($timestamp === false) {
            return "Data inválida";
        }

        // Retorna o dia da semana em português
        $diasDaSemana = [
            'Sunday' => 'Domingo',
            'Monday' => 'Segunda-feira',
            'Tuesday' => 'Terça-feira',
            'Wednesday' => 'Quarta-feira',
            'Thursday' => 'Quinta-feira',
            'Friday' => 'Sexta-feira',
            'Saturday' => 'Sábado'
        ];

        // Obtém o dia da semana em inglês
        $diaSemana = date('l', $timestamp);

        // Retorna o dia da semana em português
        return $diasDaSemana[$diaSemana];
    }

    public function ajustarData($data, int $dias, $operacao = 'adicionar')
    {
        // Tenta criar um objeto DateTime a partir da data fornecida
        try {
            $date = new DateTime($data);
            $intervalo = new DateInterval('P' . abs($dias) . 'D');
            ($operacao === 'subtrair') ? $date->sub($intervalo) : $date->add($intervalo);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return
                "ERROR: {$e->getMessage()}";
        }
    }

    public function retornarDiaUtil($data_desligamento)
    {
        $dia_semana = strtolower($this->retornarDiaDaSemana($data_desligamento));

        $dias_ajuste = [
            'sexta-feira' => 3,
            'sábado' => 2,
            'domingo' => 1,
        ];

        $dias_ajuste_default = 1;

        $dias_para_ajustar = isset($dias_ajuste[$dia_semana]) ? $dias_ajuste[$dia_semana] : $dias_ajuste_default;

        return $this->ajustarData($data_desligamento, $dias_para_ajustar);
    }

    public function formatarData($data, $formato = "d/m/Y")
    {
        $data_obj = DateTime::createFromFormat('Y-m-d', $data);

        if ($data_obj && $data_obj->format('Y-m-d') === $data) {
            return $data_obj->format($formato);
        }


        return "-";
    }
}
