# Cotidfiano
[![maintainer](https://img.shields.io/badge/maintainer-leomottarocha-blue)](https://github.com/leomottarocha)
[![source](https://img.shields.io/badge/source-leomottarocha/cotidiano-blue)](https://github.com/leomottarocha/cotidiano)
[![php](https://img.shields.io/packagist/php-v/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)
[![release](https://img.shields.io/packagist/v/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)
[![license](https://img.shields.io/packagist/l/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)
[![downloads](https://img.shields.io/packagist/dt/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)

# Cotidiano (PHP Utilities)

Conjunto de utilitários em PHP para o dia a dia de desenvolvimento, com foco em **formatação/validação de CPF/CNPJ**, **datas e horários**, **sanitização de strings**, **geração de senhas**, **operações básicas com PDO** (SELECT/INSERT/UPDATE/DELETE com retornos padronizados) e **utilitários HTTP** (ViaCEP e verificação de URL).

> **Namespace:** `Source\Models`  
> **Classe:** `final Cotidiano`  
> **Requisitos:** PHP **8.1+** (recomendado 8.2/8.3), PDO habilitado e `ext-curl` para métodos HTTP.

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

Coloque o arquivo `Cotidiano.php` em `src/Models/` (ou ajuste conforme sua estrutura) e rode:

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
- `validarCpf()` / `validarCnpj()` → valida dígitos verificadores  

🕒 **Datas e Tempo**  
- `contarTempo()` → diferença entre duas datas em minutos/horas/dias/meses/anos  
- `retornarDiaDaSemana()` → retorna o dia da semana em português  
- `ajustarData()` → soma ou subtrai dias/meses/anos  
- `retornarDiaUtil()` → avança para o próximo dia útil (regra simples)  
- `formatarData()` → converte de `Y-m-d` para outro formato  

🧠 **Strings e Textos**  
- `letrasMinusculas()` → normaliza array em minúsculas sem duplicados  
- `removerAcentos()` → remove acentuação de forma segura  

🔐 **Segurança**  
- `gerarSenhaRandomica()` → gera senha forte com `random_int()`  

🗄️ **Banco de Dados (PDO)**  
- `selecionarDados()` → SELECT padronizado  
- `cadastrarDados()` → INSERT com `lastInsertId()`  
- `atualizarDados()` → UPDATE (exige `WHERE`)  
- `deletarDados()` → DELETE (exige `WHERE`)  

🌐 **HTTP Utilitários**  
- `consultarCEP()` → busca CEP via **ViaCEP**  
- `urlValida()` → valida formato e, opcionalmente, checa status HTTP (HEAD/redirects)  

> **Mudanças recentes**  
> - Classe marcada como **`final`**  
> - **Novo:** `consultarCEP(cep)` (ViaCEP, `ext-curl`)  
> - **Novo:** `urlValida(url, checkOnline=false)` (validação + verificação online opcional)  


---

## 📚 Tabela de Métodos

| Método | Descrição | Exemplo |
|:--|:--|:--|
| `somenteNumeros($valor)` | Mantém apenas dígitos. | `(21) 99999-0000 → 21999990000` |
| `mascararCpf($cpf)` | Aplica máscara 000.000.000-00. | `12345678901 → 123.456.789-01` |
| `mascararCnpj($cnpj)` | Aplica máscara 00.000.000/0000-00. | `11222333000181 → 11.222.333/0001-81` |
| `validarCpf($cpf)` | Valida CPF. | `true/false` |
| `validarCnpj($cnpj)` | Valida CNPJ. | `true/false` |
| `contarTempo($ini,$fim,$unidade,$tz)` | Diferença em minutos/horas/dias/meses/anos. | `"9 dias"` |
| `retornarDiaDaSemana($data)` | Dia da semana em pt-BR. | `"Quarta-feira"` |
| `ajustarData($data,$dias,$periodo)` | Soma/subtrai D/M/Y. | `2025-10-25` |
| `retornarDiaUtil($data)` | Próximo dia útil (regra simples). | `2025-10-27` |
| `formatarData($data,$formato)` | Formata `Y-m-d` em outro formato. | `22/10/2025` |
| `gerarSenhaRandomica($tamanho)` | Senha forte com símbolos. | `"A9@bZ..."` |
| `removerAcentos($string)` | Remove acentuação. | `"ação" → "acao"` |
| `selecionarDados($sql,$conn)` | SELECT (PDO) com retorno padronizado. | — |
| `cadastrarDados($sql,$conn)` | INSERT (PDO) com `lastInsertId()`. | — |
| `atualizarDados($sql,$conn)` | UPDATE (PDO) – exige `WHERE`. | — |
| `deletarDados($sql,$conn)` | DELETE (PDO) – exige `WHERE`. | — |
| `consultarCEP($cep)` | ViaCEP (`ext-curl`). | `consultarCEP('01001-000')` |
| `urlValida($url,$checkOnline=false)` | Valida formato e opcionalmente checa HTTP. | `urlValida('https://.../', true)` |

---

## 💡 Exemplos de Uso

### 📄 Documentos & Strings

```php
$c->somenteNumeros('(21) 99999-0000');     // "21999990000"
$c->mascararCpf('12345678901');            // "123.456.789-01"
$c->validarCnpj('11.222.333/0001-81');     // true/false
$c->removerAcentos('João da Silva');       // "Joao da Silva"
```

### 🕒 Datas e Tempo

```php
$c->contarTempo('2025-01-01','2025-01-10','dias'); // "9 dias"
$c->retornarDiaDaSemana('2025-10-22');             // "Quarta-feira"
$c->ajustarData('2025-10-22', 3);                  // "2025-10-25"
$c->retornarDiaUtil('2025-10-24');                 // "2025-10-27"
$c->formatarData('2025-10-22');                    // "22/10/2025"
$c->gerarSenhaRandomica(20);                       // "..."
```

### 🧮 Banco de Dados (PDO)

```php
use PDO;
use Source\Models\Cotidiano;

$pdo = new PDO('mysql:host=localhost;dbname=app;charset=utf8mb4', 'user', 'pass', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$c = new Cotidiano();

// SELECT
$r = $c->selecionarDados("SELECT id,nome FROM clientes LIMIT 10", $pdo);

// INSERT
$r = $c->cadastrarDados("
  INSERT INTO clientes (nome, email) VALUES ('Ana','ana@exemplo.com')
", $pdo);

// UPDATE (com WHERE)
$r = $c->atualizarDados("
  UPDATE clientes SET nome='Ana Maria' WHERE id=123
", $pdo);

// DELETE (com WHERE)
$r = $c->deletarDados("
  DELETE FROM clientes WHERE id=123
", $pdo);
```

### 🌐 HTTP Utilitários

```php
// ViaCEP
$cep = $c->consultarCEP('01001-000'); // array ou null

// Verificação de URL (somente formato)
$check = $c->urlValida('https://www.linkedin.com/in/usuario');

// Verificação de URL (formato + online)
$check = $c->urlValida('https://www.linkedin.com/in/usuario', true);
/*
[
  'url' => 'https://...',
  'formato_valido' => true,
  'http_status' => 999, // exemplo
  'acessivel' => true,
  'erro' => null
]
*/
```

---

## 📤 Padrão de Retorno (PDO)

Todos os métodos de banco retornam um array associativo:

```php
[
  "status"          => bool,
  "msg_erro"        => string,
  "total_registros" => int,
  "data"            => array|mixed
]
```

Mensagens comuns:
- `"Não há registros com a instrução SQL: ..."` quando `rowCount() === 0`
- Exceções de `PDOException` retornadas em `"msg_erro"`

---

## 🔒 Boas Práticas e Segurança

- **SQL dinâmico:** use **prepared statements** e `bindValue()` antes de passar a query final quando envolver dados de usuário.  
- **Proteção:** `UPDATE` e `DELETE` **exigem `WHERE`** — bloqueio por design.  
- **HTTP:** sites como LinkedIn podem bloquear bots; ainda assim, códigos HTTP > 0 indicam resposta.  
- **Timezone:** `contarTempo()` aceita *timezone* (padrão `America/Sao_Paulo`).

---

## ⚙️ Compatibilidade

- PHP **8.1+**  
- Extensões: `PDO`, `mbstring`, `json`, `ctype`, `curl`  
- Testado em **MariaDB 10.4+** e **MySQL 8.0+**

---

## 🧭 Roadmap

- [ ] Prepared statements internos opcionais (helper)  
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

### v1.1.0
- **Novo:** `consultarCEP()` (ViaCEP, `ext-curl`)  
- **Novo:** `urlValida()` (validação e verificação online opcional)  
- **Alteração:** classe marcada como **`final`**  
- **Docs:** README e PHPDoc atualizados

### v1.0.0
- Versão inicial com CPF/CNPJ, datas/tempo, strings, senhas e CRUD PDO básicos.

---

## 📄 Licença

Distribuído sob a **Licença MIT**. Uso livre para fins pessoais e comerciais.

---

### 💬 Créditos

Desenvolvido por **Léo Motta Rocha**  
[LinkedIn](https://www.linkedin.com/in/leomottarocha) • [GitHub](https://github.com/leomottarocha)

Se esta classe te ajudou, ⭐ **deixe uma estrela** no repositório!
