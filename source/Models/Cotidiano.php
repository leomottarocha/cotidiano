<?php

namespace Source\Models;

use \DateTime;
use \DateTimeZone;
use \PDOException;

class Cotidiano
{

    function contarDias($dataInicio, $dataTermino, $timeZone = 'America/Sao_Paulo')
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
}
