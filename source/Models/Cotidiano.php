<?php

namespace Source\Models;

use \DateTime;
use \DateInterval;
use \Exception;
use \DateTimeZone;
use \PDOException;

class Cotidiano
{

    public  function selecionarDados($instrucaoSql, $conn)
    {
        $data = [];
        if (!strstr(mb_strtolower($instrucaoSql ?? ""), "select")) {
            $data = [
                "status" => false,
                "msg_erro" => "A instrução select está incorreta: {$instrucaoSql}",
                "data" => []
            ];
        } else {
            try {
                $sql = $conn->prepare($instrucaoSql);
                $sql->execute();
                if ($sql->rowCount() > 0) {
                    $data = [
                        "status" => true,
                        "msg_erro" => "",
                        "total_registros" => $sql->rowCount(),
                        "data" => $sql->fetchAll()
                    ];
                } else {
                    $data = [
                        "status" => false,
                        "msg_erro" => "Não há registros com a instrução SQL: {$instrucaoSql}",
                        "total_registros" => $sql->rowCount(),
                        "data" => []
                    ];
                }
            } catch (PDOException $e) {
                $data = [
                    "status" => false,
                    "msg_erro" => $e->getMessage(),
                    "data" => []
                ];
            }
        }
        return $data;
    }

    public  function atualizarDados($instrucaoSql, $conn)
    {
        $data = [];

        if (!strstr(mb_strtolower($instrucaoSql ?? ""), "where") or !strstr(mb_strtolower($instrucaoSql ?? ""), "update")) {
            $data = [
                "status" => false,
                "msg_erro" => "Não é permitido realizar um update sem a clausula WHERE: {$instrucaoSql}",
                "data" => []
            ];
        } else {
            try {
                $sql = $conn->prepare($instrucaoSql);
                $sql->execute();
                if ($sql->rowCount() > 0) {
                    $data = [
                        "status" => true,
                        "msg_erro" => "",
                        "total_registros" => $sql->rowCount(),
                        "data" => []
                    ];
                } else {
                    $data = [
                        "status" => false,
                        "msg_erro" => "Não há registros com a instrução SQL: {$instrucaoSql}",
                        "total_registros" => $sql->rowCount(),
                        "data" => []
                    ];
                }
            } catch (PDOException $e) {
                $data = [
                    "status" => false,
                    "msg_erro" => $e->getMessage(),
                    "data" => []
                ];
            }
        }
        return $data;
    }

    public  function deletarDados($instrucaoSql, $conn)
    {
        $data = [];

        if (!strstr(mb_strtolower($instrucaoSql ?? ""), "where") or !strstr(mb_strtolower($instrucaoSql ?? ""), "delete")) {
            $data = [
                "status" => false,
                "msg_erro" => "Não é permitido realizar um delete sem a clausula WHERE: {$instrucaoSql}",
                "data" => []
            ];
        } else {
            try {
                $sql = $conn->prepare($instrucaoSql);
                $sql->execute();
                if ($sql->rowCount() > 0) {
                    $data = [
                        "status" => true,
                        "msg_erro" => "",
                        "total_registros" => $sql->rowCount(),
                        "data" => []
                    ];
                } else {
                    $data = [
                        "status" => false,
                        "msg_erro" => "Não há registros com a instrução SQL: {$instrucaoSql}",
                        "total_registros" => $sql->rowCount(),
                        "data" => []
                    ];
                }
            } catch (PDOException $e) {
                $data = [
                    "status" => false,
                    "msg_erro" => $e->getMessage(),
                    "data" => []
                ];
            }
        }
        return $data;
    }

    public  function cadastrarDados($instrucaoSql, $conn)
    {
        $data = [];

        if (!strstr(mb_strtolower($instrucaoSql ?? ""), "insert")) {
            $data = [
                "status" => false,
                "msg_erro" => "A clausula insert está incorreta: {$instrucaoSql}",
                "data" => []
            ];
        } else {
            try {
                $sql = $conn->prepare($instrucaoSql);
                $sql->execute();
                if ($sql->rowCount() > 0) {

                    $data = [
                        "status" => true,
                        "msg_erro" => "",
                        "total_registros" => $sql->rowCount(),
                        "data" => [
                            "id" => $conn->lastInsertId(),
                            "sql" => $instrucaoSql
                        ]
                    ];
                } else {
                    $data = [
                        "status" => false,
                        "msg_erro" => "Não há registros com a instrução SQL: {$instrucaoSql}",
                        "total_registros" => $sql->rowCount(),
                        "data" => []
                    ];
                }
            } catch (PDOException $e) {
                $data = [
                    "status" => false,
                    "msg_erro" => $e->getMessage(),
                    "data" => []
                ];
            }
        }
        return $data;
    }

    public function contarTempo($dataInicio, $dataTermino, $unidade = 'dias', $timeZone = 'America/Sao_Paulo')
    {
        try {
            // Configura o fuso horário
            $timeZone = new DateTimeZone($timeZone);
            $dataInicio = new DateTime($dataInicio, $timeZone);
            $dataTermino = new DateTime($dataTermino, $timeZone);

            // Calcula o intervalo entre as duas datas
            $intervalo = $dataInicio->diff($dataTermino);

            // Verifica a inversão do intervalo para ajustar o sinal
            $invertido = $intervalo->invert == 1 ? -1 : 1;

            // Calcula o total de minutos, horas, dias, meses e anos
            $totalMinutos = $intervalo->days * 24 * 60 + $intervalo->h * 60 + $intervalo->i;
            $totalHoras = $intervalo->days * 24 + $intervalo->h;
            $totalDias = $intervalo->days;
            $totalMeses = $intervalo->y * 12 + $intervalo->m;
            $totalAnos = $intervalo->y;

            // Retorna o resultado de acordo com a unidade especificada
            switch (mb_strtolower($unidade ?? "")) {
                case 'minutos':
                    return $totalMinutos * $invertido . " minutos";
                case 'horas':
                    return $totalHoras * $invertido . " horas";
                case 'dias':
                    return $totalDias * $invertido . " dias";
                case 'meses':
                    return $totalMeses * $invertido . " meses";
                case 'anos':
                    return $totalAnos * $invertido . " anos";
                default:
                    throw new Exception('Unidade de tempo inválida. Use: minutos, horas, dias, meses ou anos.');
            }
        } catch (Exception $exception) {
            return 'Erro: ' . $exception->getMessage();
        }
    }

    /**
     * Esta função está obsoleta e será removida em versões futuras.
     * @deprecated
     */
    public function contarDias($dataInicio, $dataTermino, $timeZone = 'America/Sao_Paulo')
    {

        trigger_error("A função 'contarDias' está obsoleta e será removida em versões futuras.", E_USER_DEPRECATED);

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

    public function ajustarData($data, int $dias, string $periodo = "Days")
    {
        try {
            $date = new DateTime($data);
            $intervalo = new DateInterval('P' . abs($dias) . $periodo[0]);
            ($dias < 0) ? $date->sub($intervalo) : $date->add($intervalo);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return "ERROR: {$e->getMessage()}";
        }
    }

    public function retornarDiaUtil($data_desligamento)
    {
        $dia_semana = mb_strtolower($this->retornarDiaDaSemana($data_desligamento) ?? "");

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
        // Verifica se o valor de $data é uma string não vazia
        if (empty($data) || !is_string($data)) {
            return "-";
        }

        // Tenta criar o objeto DateTime com o formato 'Y-m-d'
        $data_obj = DateTime::createFromFormat('Y-m-d', $data);

        // Verifica se a data foi criada corretamente e corresponde ao formato 'Y-m-d'
        if ($data_obj && $data_obj->format('Y-m-d') === $data) {
            // Retorna a data formatada de acordo com o formato desejado
            return $data_obj->format($formato);
        }

        // Se a data não for válida, retorna "-"
        return "-";
    }

    public function gerarSenhaRandomica($tamanho = 16)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#{}[];()-_+*!';
        $alphaLength = strlen($alphabet); // Comprimento total do alfabeto
        $senha = '';

        for ($i = 0; $i < $tamanho; $i++) {
            // Gera um número aleatório seguro
            $n = random_int(0, $alphaLength - 1);
            $senha .= $alphabet[$n]; // Concatena diretamente na variável de senha
        }

        return $senha;
    }

    function removerAcentos(string $string)
    {
        // Substitui os caracteres acentuados pelos correspondentes sem acento
        $acentos = [
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ä' => 'a',
            'á' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'í' => 'i',
            'ì' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ó' => 'o',
            'ò' => 'o',
            'õ' => 'o',
            'ô' => 'o',
            'ö' => 'o',
            'ú' => 'u',
            'ù' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'Ç' => 'C',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'Ä' => 'A',
            'É' => 'E',
            'È' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Í' => 'I',
            'Ì' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ó' => 'O',
            'Ò' => 'O',
            'Õ' => 'O',
            'Ô' => 'O',
            'Ö' => 'O',
            'Ú' => 'U',
            'Ù' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N',
            'ń' => 'n',
            'Ń' => 'N',
            'ś' => 's',
            'Ś' => 'S',
            'Ź' => 'Z',
            'ź' => 'z',

        ];

        return strtr($string, $acentos);
    }
}
