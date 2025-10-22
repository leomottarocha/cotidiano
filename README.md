# Cotidiano (PHP Utilities)

Conjunto de utilitários em PHP para o dia a dia de desenvolvimento, com foco em **formatação/validação de CPF/CNPJ**, **datas e horários**, **sanitização de strings**, **geração de senhas** e **operações básicas com PDO** (SELECT/INSERT/UPDATE/DELETE com retornos padronizados).

> **Namespace:** `Source\Models`  
> **Classe:** `Cotidiano`  
> **Requisitos:** PHP **8.1+** (recomendado 8.2/8.3), PDO habilitado.

---

## 📦 Instalação

**Via Composer (autoload do seu projeto)**

```json
{
  "autoload": {
    "psr-4": {
      "Source\\Models\\": "src/Models/"
    }
  }
}
```

Salve o arquivo `Cotidiano.php` em `src/Models/` (ou ajuste conforme sua estrutura) e rode:

```bash
composer dump-autoload
```

---

## 🚀 Inicialização

```php
<?php

use Source\Models\Cotidiano;

$c = new Cotidiano();
```

---

## 🧩 Principais Recursos

✅ **Documentos (Brasil)**  
- `somenteNumeros()` → remove tudo que não é número  
- `mascararCpf()` / `mascararCnpj()` → aplica máscara  
- `validarCpf()` / `validarCnpj()` → valida os dígitos verificadores  

🕒 **Datas e Tempo**  
- `contarTempo()` → diferença entre duas datas em minutos/horas/dias/meses/anos  
- `retornarDiaDaSemana()` → retorna o dia da semana em português  
- `ajustarData()` → soma ou subtrai dias, meses ou anos  
- `retornarDiaUtil()` → avança para o próximo dia útil  
- `formatarData()` → converte de `Y-m-d` para `d/m/Y` ou outro formato  

🧠 **Strings e Textos**  
- `letrasMinusculas()` → normaliza array em minúsculas (sem duplicar)  
- `removerAcentos()` → remove acentuação de forma segura  

🔐 **Segurança**  
- `gerarSenhaRandomica()` → gera senha forte e aleatória com `random_int()`  

🗄️ **Banco de Dados (PDO)**  
- `selecionarDados()` → SELECT padronizado  
- `cadastrarDados()` → INSERT com `lastInsertId()`  
- `atualizarDados()` → UPDATE (exige WHERE)  
- `deletarDados()` → DELETE (exige WHERE)  

---

## 📚 Tabela de Métodos

| Método | Descrição | Exemplo |
|:--|:--|:--|
| `somenteNumeros($valor)` | Remove tudo que não é número. | `(21) 99999-0000 → 21999990000` |
| `mascararCpf($cpf)` | Aplica máscara 000.000.000-00. | `12345678901 → 123.456.789-01` |
| `mascararCnpj($cnpj)` | Aplica máscara 00.000.000/0000-00. | `11222333000181 → 11.222.333/0001-81` |
| `validarCpf($cpf)` | Valida CPF. | `true/false` |
| `validarCnpj($cnpj)` | Valida CNPJ. | `true/false` |
| `contarTempo($inicio,$fim,'dias')` | Diferença em dias, horas, etc. | `9 dias` |
| `retornarDiaDaSemana($data)` | Dia da semana em pt-BR. | `Quarta-feira` |
| `ajustarData($data, $dias)` | Soma/subtrai dias. | `2025-10-25` |
| `retornarDiaUtil($data)` | Próximo dia útil. | — |
| `formatarData($data)` | Converte formato da data. | `22/10/2025` |
| `gerarSenhaRandomica($tamanho)` | Gera senha forte. | `"A9@bZ..."` |
| `removerAcentos($string)` | Remove acentuação. | `"ação" → "acao"` |
| `selecionarDados($sql,$conn)` | SELECT genérico (PDO). | — |
| `cadastrarDados($sql,$conn)` | INSERT genérico. | — |
| `atualizarDados($sql,$conn)` | UPDATE (requer WHERE). | — |
| `deletarDados($sql,$conn)` | DELETE (requer WHERE). | — |

---

## 💡 Exemplos de Uso

### 📄 Strings e Documentos

```php
$c->somenteNumeros('(21) 99999-0000');   // "21999990000"
$c->mascararCpf('12345678901');          // "123.456.789-01"
$c->validarCnpj('11.222.333/0001-81');   // true/false
$c->removerAcentos('João da Silva');     // "Joao da Silva"
```

---

### 🕒 Datas e Tempo

```php
$c->contarTempo('2025-01-01','2025-01-10','dias'); // "9 dias"
$c->retornarDiaDaSemana('2025-10-22');             // "Quarta-feira"
$c->ajustarData('2025-10-22', 3);                  // "2025-10-25"
$c->retornarDiaUtil('2025-10-24');                 // "2025-10-27"
$c->formatarData('2025-10-22');                    // "22/10/2025"
$c->gerarSenhaRandomica(20);                       // "Lk@!z..."
```

---

### 🧮 Banco de Dados (PDO)

```php
use PDO;
use Source\Models\Cotidiano;

$pdo = new PDO('mysql:host=localhost;dbname=app;charset=utf8mb4', 'user', 'pass', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$c = new Cotidiano();

// SELECT
$result = $c->selecionarDados("SELECT * FROM clientes", $pdo);

// INSERT
$result = $c->cadastrarDados("
  INSERT INTO clientes (nome, email) VALUES ('Ana','ana@exemplo.com')
", $pdo);

// UPDATE (com WHERE)
$result = $c->atualizarDados("
  UPDATE clientes SET nome='Ana Maria' WHERE id=123
", $pdo);

// DELETE (com WHERE)
$result = $c->deletarDados("
  DELETE FROM clientes WHERE id=123
", $pdo);
```

---

## 📤 Padrão de Retorno (PDO)

```php
[
  "status"          => bool,
  "msg_erro"        => string,
  "total_registros" => int,
  "data"            => array|mixed
]
```

---

## 🔒 Boas Práticas e Segurança

- Use **prepared statements** e **bindValue()** ao montar SQL dinâmico.  
- `UPDATE` e `DELETE` **bloqueiam instruções sem WHERE**.  
- Retorna mensagens detalhadas em caso de erro (`msg_erro`).  
- `contarDias()` está **obsoleta** — use `contarTempo($a,$b,'dias')`.  

---

## ⚙️ Compatibilidade

- PHP **8.1+**
- Extensões: `PDO`, `mbstring`, `json`, `ctype`
- Testado em **MariaDB 10.4+** e **MySQL 8.0+**

---

## 🧭 Roadmap

- [ ] Implementar prepared statements automáticos  
- [ ] Normalizador de datas multi-formato (`d/m/Y`, `Y-m-d H:i`, etc.)  
- [ ] Máscaras genéricas (telefone, CEP)  
- [ ] Conversão de moedas e formatação pt-BR ↔ ISO  

---

## 🤝 Contribuição

Pull requests são bem-vindos!  
Antes de contribuir:
1. Crie um *fork* do repositório  
2. Crie uma *branch* (`feature/nova-funcao`)  
3. Envie seu PR com descrição e testes  

---

## 🧾 Changelog

### v1.0.0
- Primeira versão pública  
- Validação e formatação de CPF/CNPJ  
- Funções de datas, strings e senhas  
- CRUD PDO com validações básicas  

---

## 📄 Licença

Distribuído sob a **Licença MIT**.  
Uso livre para fins pessoais e comerciais.

---

### 💬 Créditos

Desenvolvido por **Léo Motta Rocha**  
[LinkedIn](https://www.linkedin.com/in/leomottarocha) • [GitHub](https://github.com/leomottarocha)

Se esta classe te ajudou, ⭐ **deixe uma estrela** no repositório!
