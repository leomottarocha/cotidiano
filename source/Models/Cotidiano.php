<?php

declare(strict_types=1);

namespace Source\Models;

use \DateTime;
use \DateInterval;
use \Exception;
use \DateTimeZone;
use \PDOException;
use \PDO;

final class Cotidiano
{

    public function somenteNumeros(?string $valor): string
    {
        return preg_replace('/\D+/', '', $valor ?? '');
    }

    public function mascararCpf(string $cpf): string
    {
        $d = preg_replace('/\D+/', '', $cpf ?? '');
        if (strlen($d) !== 11) return $cpf;
        return substr($d, 0, 3) . '.' . substr($d, 3, 3) . '.' . substr($d, 6, 3) . '-' . substr($d, 9, 2);
    }

    public function mascararCnpj(string $cnpj): string
    {
        $d = preg_replace('/\D+/', '', $cnpj ?? '');
        if (strlen($d) !== 14) return $cnpj;
        return substr($d, 0, 2) . '.' . substr($d, 2, 3) . '.' . substr($d, 5, 3) . '/' . substr($d, 8, 4) . '-' . substr($d, 12, 2);
    }
    public function validarCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D+/', '', $cnpj ?? '');
        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }
        $peso1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $peso2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        $calc = function (array $peso, string $num): int {
            $sum = 0;
            foreach ($peso as $i => $p) {
                $sum += (int)$num[$i] * $p;
            }
            $resto = $sum % 11;
            return ($resto < 2) ? 0 : 11 - $resto;
        };

        $d1 = $calc($peso1, substr($cnpj, 0, 12));
        $d2 = $calc($peso2, substr($cnpj, 0, 12) . $d1);
        return $cnpj[12] == (string)$d1 && $cnpj[13] == (string)$d2;
    }

    public function validarCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D+/', '', $cpf ?? '');
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += (int)$cpf[$i] * (($t + 1) - $i);
            }
            $d = ((10 * $sum) % 11) % 10;
            if ($cpf[$t] != $d) {
                return false;
            }
        }
        return true;
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

    public function gerarSenhaRandomica(int $tamanho = 16)
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

    public function removerAcentos(string $string)
    {
        // Substitui os caracteres acentuados pelos correspondentes sem acento
        $acentos = [
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ä' => 'a',
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

    public function consultarCEP(string $cep): ?array
    {
        // Sanitiza o CEP (remove tudo que não for número)
        $cep = preg_replace('/\D/', '', $cep);

        // Verifica formato válido (8 dígitos)
        if (strlen($cep) !== 8) {
            return null;
        }

        // Monta a URL
        $url = "https://viacep.com.br/ws/{$cep}/json/";

        // Usa cURL para maior controle e timeout
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Verifica se houve erro na requisição
        if ($response === false || $httpCode !== 200) {
            return null;
        }

        // Decodifica o JSON
        $data = json_decode($response, true);

        // Verifica se o CEP existe (ViaCEP retorna {"erro": true} quando não encontra)
        if (isset($data['erro']) && $data['erro'] === true) {
            return null;
        }

        return $data;
    }

    public function urlValida(string $url, bool $checkOnline = true): array
    {
        $resultado = [
            'url' => $url,
            'formato_valido' => false,
            'http_status' => null,
            'acessivel' => false,
            'erro' => null
        ];

        // ✅ Validação de formato
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $resultado['erro'] = 'Formato inválido';
            return $resultado;
        }

        $resultado['formato_valido'] = true;

        // 🔄 Sem verificação online? retorna aqui
        if (!$checkOnline) {
            return $resultado;
        }

        // ⚙️ Configuração cURL realista
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 7,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
            CURLOPT_HTTPHEADER => [
                'Accept-Language: en-US,en;q=0.9',
                'Connection: keep-alive'
            ]
        ]);

        $exec = curl_exec($ch);

        if ($exec === false) {
            $resultado['erro'] = curl_error($ch);
            curl_close($ch);
            return $resultado;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $resultado['http_status'] = $httpCode;

        // 🔍 LinkedIn e outros sites bloqueiam bots, mas isso ainda é uma resposta válida
        if ($httpCode > 0) {
            $resultado['acessivel'] = true;
        }

        return $resultado;
    }


    /**
     * Banco de dados
     */
    public function insert(string $table, array $data, PDO $conn): array
    {
        // (opcional, mas recomendado) valida nomes
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            return ["status" => false, "msg_erro" => "Tabela inválida: {$table}", "data" => []];
        }

        $cols = array_keys($data);
        if (!$cols) {
            return ["status" => false, "msg_erro" => "Array vazio.", "data" => []];
        }

        foreach ($cols as $c) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $c)) {
                return ["status" => false, "msg_erro" => "Coluna inválida: {$c}", "data" => []];
            }
        }

        $columns = implode(", ", array_map(fn($c) => "`{$c}`", $cols));
        $params  = implode(", ", array_map(fn($c) => ":{$c}", $cols));

        $sql = "INSERT INTO `{$table}` ({$columns}) VALUES ({$params})";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($data);

            return [
                "status" => true,
                "msg_erro" => "",
                "total_registros" => $stmt->rowCount(),
                "data" => [
                    "id" => $conn->lastInsertId(),
                    "sql" => $sql
                ]
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                "status" => false,
                "msg_erro" => $e->getMessage(),
                "data" => ["sql" => $sql]
            ];
        }
    }

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
                error_log($e->getMessage());
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
                error_log($e->getMessage());
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
                error_log($e->getMessage());
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
                error_log($e->getMessage());
            }
        }
        return $data;
    }
}
