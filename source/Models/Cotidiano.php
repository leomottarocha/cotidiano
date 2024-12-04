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
        if (!strstr(strtolower($instrucaoSql), "select")) {
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

        if (!strstr(strtolower($instrucaoSql), "where") or !strstr(strtolower($instrucaoSql), "update")) {
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

        if (!strstr(strtolower($instrucaoSql), "where") or !strstr(strtolower($instrucaoSql), "delete")) {
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

        if (!strstr(strtolower($instrucaoSql), "insert")) {
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

    public function ajustarData($data, int $dias)
    {
        try {
            $date = new DateTime($data);
            $intervalo = new DateInterval('P' . abs($dias) . 'D');
            ($dias < 0) ? $date->sub($intervalo) : $date->add($intervalo);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            return "ERROR: {$e->getMessage()}";
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
}
