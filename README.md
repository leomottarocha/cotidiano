# Cotidfiano
[![maintainer](https://img.shields.io/badge/maintainer-leomottarocha-blue)](https://github.com/leomottarocha)
[![source](https://img.shields.io/badge/source-leomottarocha/cotidiano-blue)](https://github.com/leomottarocha/cotidiano)
[![php](https://img.shields.io/packagist/php-v/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)
[![release](https://img.shields.io/packagist/v/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)
[![license](https://img.shields.io/packagist/l/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)
[![downloads](https://img.shields.io/packagist/dt/leomottarocha/cotidiano)](https://packagist.org/packages/leomottarocha/cotidiano)

# Cotidiano (PHP Utilities)

Conjunto de utilitários em PHP para o dia a dia de desenvolvimento, com foco em **formatação/validação de CPF/CNPJ**, **datas e horários**, **sanitização de strings**, **geração de senhas**, **operações com PDO** (SELECT/INSERT/UPDATE/DELETE com retornos padronizados) e **utilitários HTTP** (ViaCEP e verificação de URL).

> **Namespace:** `Source\Models`  
> **Classes:** `final Cotidiano`, `final Cpf`, `final Cnpj`  
> **Requisitos:** PHP **8.1+** (recomendado 8.2/8.3), PDO habilitado e `ext-curl` para métodos HTTP.

---

## 📦 Instalação

**Via Composer (autoload do seu projeto)**

```json
{
  "autoload": {
    "psr-4": {
      "Source\\Models\\": "source/Models/"
    }
  }
}
```

Coloque os arquivos `Cotidiano.php`, `CPF.php` e `CNPJ.php` em `source/Models/` (ou ajuste conforme sua estrutura) e rode:

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
- `limparCpf()` / `limparCnpj()` → remove máscara e normaliza documento  
- `cnpjNumerico()` / `cnpjAlfanumerico()` → diferencia o formato do CNPJ  

🕒 **Datas e Tempo**  
- `contarTempo()` → diferença entre duas datas em segundos/minutos/horas/dias/semanas/meses/anos  
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
- `insert()` → INSERT com placeholders nomeados e `lastInsertId()`  
- `selecionarDados()` → SELECT padronizado  
- `cadastrarDados()` → INSERT a partir de SQL livre com `lastInsertId()`  
- `atualizarDados()` → UPDATE (exige `WHERE`)  
- `deletarDados()` → DELETE (exige `WHERE`)  

🌐 **HTTP Utilitários**  
- `consultarCEP()` → busca CEP via **ViaCEP**  
- `urlValida()` → valida formato e, opcionalmente, checa status HTTP (HEAD/redirects)  

> **Mudanças recentes**  
> - Classe `Cotidiano` marcada como **`final`**  
> - **Novo:** separação das responsabilidades em `Cpf` e `Cnpj`, mantendo `Cotidiano` como fachada  
> - **Novo:** `limparCpf()` e `limparCnpj()`  
> - **Novo:** suporte a **CNPJ alfanumérico**, sem perder compatibilidade com o formato tradicional  
> - **Novo:** `insert($table, array $data, PDO $conn)` com colunas parametrizadas  
> - **Novo:** `consultarCEP(cep)` (ViaCEP, `ext-curl`)  
> - **Novo:** `urlValida(url, checkOnline=true)` (validação + verificação online opcional)  

---

## 📚 Tabela de Métodos

| Método | Descrição | Exemplo |
|:--|:--|:--|
| `somenteNumeros($valor)` | Mantém apenas dígitos. | `(21) 99999-0000 → 21999990000` |
| `mascararCpf($cpf)` | Aplica máscara `000.000.000-00`. | `12345678901 → 123.456.789-01` |
| `validarCpf($cpf)` | Valida CPF. | `true/false` |
| `limparCpf($cpf)` | Remove máscara do CPF. | `123.456.789-01 → 12345678901` |
| `mascararCnpj($cnpj)` | Aplica máscara `00.000.000/0000-00`. | `11222333000181 → 11.222.333/0001-81` |
| `validarCnpj($cnpj)` | Valida CNPJ numérico ou alfanumérico. | `true/false` |
| `limparCnpj($cnpj)` | Remove máscara e normaliza CNPJ. | `11.222.333/0001-81 → 11222333000181` |
| `cnpjNumerico($cnpj)` | Verifica se o CNPJ está no formato numérico. | `true/false` |
| `cnpjAlfanumerico($cnpj)` | Verifica se o CNPJ está no formato alfanumérico. | `true/false` |
| `contarTempo($ini,$fim,$unidade,$comTexto,$tz)` | Diferença em segundos/minutos/horas/dias/semanas/meses/anos. | `"9 dias"` |
| `retornarDiaDaSemana($data)` | Dia da semana em pt-BR. | `"Quarta-feira"` |
| `ajustarData($data,$dias,$periodo)` | Soma/subtrai D/M/Y. | `2025-10-25` |
| `retornarDiaUtil($data)` | Próximo dia útil (regra simples). | `2025-10-27` |
| `formatarData($data,$formato)` | Formata `Y-m-d` em outro formato. | `22/10/2025` |
| `gerarSenhaRandomica($tamanho)` | Senha forte com símbolos. | `"A9@bZ..."` |
| `letrasMinusculas($dados)` | Normaliza valores em minúsculas e remove duplicados. | `['A','a'] → ['a']` |
| `removerAcentos($string)` | Remove acentuação. | `"ação" → "acao"` |
| `insert($table,$data,$conn)` | INSERT parametrizado por tabela/colunas. | — |
| `selecionarDados($sql,$conn)` | SELECT (PDO) com retorno padronizado. | — |
| `cadastrarDados($sql,$conn)` | INSERT (PDO) com `lastInsertId()`. | — |
| `atualizarDados($sql,$conn)` | UPDATE (PDO) – exige `WHERE`. | — |
| `deletarDados($sql,$conn)` | DELETE (PDO) – exige `WHERE`. | — |
| `consultarCEP($cep)` | ViaCEP (`ext-curl`). | `consultarCEP('01001-000')` |
| `urlValida($url,$checkOnline=true)` | Valida formato e opcionalmente checa HTTP. | `urlValida('https://.../', true)` |

---

## 💡 Exemplos de Uso

### 📄 Documentos & Strings

```php
$c->somenteNumeros('(21) 99999-0000');     // "21999990000"
$c->mascararCpf('12345678909');            // "123.456.789-09"
$c->validarCpf('123.456.789-09');          // true/false
$c->limparCpf('123.456.789-09');           // "12345678909"

$c->mascararCnpj('11222333000181');        // "11.222.333/0001-81"
$c->validarCnpj('11.222.333/0001-81');     // true/false
$c->limparCnpj('11.222.333/0001-81');      // "11222333000181"
$c->cnpjNumerico('11222333000181');        // true
$c->cnpjAlfanumerico('12ABC34501DE35');    // true/false

$c->removerAcentos('João da Silva');       // "Joao da Silva"
$c->letrasMinusculas(['PHP', 'php']);      // ['php']
```

### 🕒 Datas e Tempo

```php
$c->contarTempo('2025-01-01', '2025-01-10', 'dias', true);          // "9 dias"
$c->contarTempo('2025-01-01 08:00', '2025-01-01 10:35', 'minutos'); // 155
$c->contarTempo('2025-01-01 08:00', '2025-01-01 10:35', 'horas_minutos'); // "2:35"
$c->retornarDiaDaSemana('2025-10-22');                              // "Quarta-feira"
$c->ajustarData('2025-10-22', 3);                                   // "2025-10-25"
$c->retornarDiaUtil('2025-10-24');                                  // "2025-10-27"
$c->formatarData('2025-10-22');                                     // "22/10/2025"
$c->gerarSenhaRandomica(20);                                        // "..."
```

### 🧮 Banco de Dados (PDO)

```php
use PDO;
use Source\Models\Cotidiano;

$pdo = new PDO('mysql:host=localhost;dbname=app;charset=utf8mb4', 'user', 'pass', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$c = new Cotidiano();

// INSERT parametrizado por array
$r = $c->insert('clientes', [
  'nome'  => 'Ana',
  'email' => 'ana@exemplo.com'
], $pdo);

// SELECT
$r = $c->selecionarDados("SELECT id,nome FROM clientes LIMIT 10", $pdo);

// INSERT com SQL livre
$r = $c->cadastrarDados("\n  INSERT INTO clientes (nome, email) VALUES ('Ana','ana@exemplo.com')\n", $pdo);

// UPDATE (com WHERE)
$r = $c->atualizarDados("\n  UPDATE clientes SET nome='Ana Maria' WHERE id=123\n", $pdo);

// DELETE (com WHERE)
$r = $c->deletarDados("\n  DELETE FROM clientes WHERE id=123\n", $pdo);
```

### 🌐 HTTP Utilitários

```php
// ViaCEP
$cep = $c->consultarCEP('01001-000'); // array ou null

// Verificação de URL (somente formato)
$check = $c->urlValida('https://www.linkedin.com/in/usuario', false);

// Verificação de URL (formato + online)
$check = $c->urlValida('https://www.linkedin.com/in/usuario');
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

Todos os métodos de banco retornam um array associativo no mesmo padrão base:

```php
[
  "status"          => bool,
  "msg_erro"        => string,
  "total_registros" => int,
  "data"            => array|mixed
]
```

Observações:
- `insert()` retorna em `data` o `id` gerado e a `sql` montada.
- `selecionarDados()` retorna `fetchAll()` em `data`.
- Quando não há registros afetados, a mensagem costuma seguir o padrão:  
  `"Não há registros com a instrução SQL: ..."`
- Exceções de `PDOException` são retornadas em `msg_erro` e registradas com `error_log()`.

---

## 🔒 Boas Práticas e Segurança

- **SQL dinâmico:** use **prepared statements** e `bindValue()` sempre que houver dados de usuário.  
- **Proteção:** `UPDATE` e `DELETE` **exigem `WHERE`** — bloqueio por design.  
- **Insert seguro:** `insert()` valida nomes de tabela/colunas e usa placeholders nomeados.  
- **HTTP:** sites como LinkedIn podem bloquear bots; ainda assim, códigos HTTP > 0 indicam resposta.  
- **Timezone:** `contarTempo()` aceita *timezone* (padrão `America/Sao_Paulo`).  
- **Documentos:** `mascararCpf()` e `mascararCnpj()` apenas formatam; a validação real deve ser feita com `validarCpf()` e `validarCnpj()`.

---

## ⚙️ Compatibilidade

- PHP **8.1+**  
- Extensões: `PDO`, `mbstring`, `json`, `ctype`, `curl`  
- Testado para cenários comuns em **MariaDB 10.4+** e **MySQL 8.0+**

---

## 🧭 Roadmap

- [ ] Prepared statements internos opcionais para `select/update/delete`  
- [ ] Normalizador de datas multi-formato (`d/m/Y`, `Y-m-d H:i`, etc.)  
- [ ] Máscaras genéricas (telefone, CEP)  
- [ ] Conversão de moedas e formatação pt-BR ↔ ISO  
- [ ] Cobertura de testes automatizados para `Cpf`, `Cnpj` e `Cotidiano`  

---

## 🤝 Contribuição

Pull requests são bem-vindos!  
Antes de contribuir:
1. Crie um *fork* do repositório  
2. Crie uma *branch* (`feature/nova-funcao`)  
3. Envie seu PR com descrição e testes  

---

## 🧾 Changelog

### v1.2.0
- **Novo:** classes especialistas `Cpf` e `Cnpj`  
- **Novo:** `limparCpf()` e `limparCnpj()` expostos pela `Cotidiano`  
- **Novo:** suporte a **CNPJ alfanumérico** com manutenção do formato tradicional  
- **Novo:** `insert()` com placeholders nomeados e validação básica de tabela/colunas  
- **Ajuste:** `Cotidiano` atua como fachada para documentos e centralizador dos utilitários  
- **Docs:** README atualizado com os novos métodos e exemplos  

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
