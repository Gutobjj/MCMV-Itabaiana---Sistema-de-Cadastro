# MCMV Itabaiana - Sistema de Cadastro

Um sistema web simples em PHP para gerenciamento de inscrições no programa Minha Casa Minha Vida (MCMV) da Prefeitura de Itabaiana.

## Índice

- [Descrição](#descrição)
- [Funcionalidades](#funcionalidades)
- [Tecnologias](#tecnologias)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Estrutura de Pastas](#estrutura-de-pastas)
- [Banco de Dados](#banco-de-dados)
- [Uso](#uso)
- [Logs e Debug](#logs-e-debug)
- [Segurança](#segurança)
- [Contribuição](#contribuição)
- [Licença](#licença)

## Descrição

Este projeto fornece um formulário de cadastro para beneficiários do programa MCMV, com validações front-end em JavaScript, upload seguro de documentos (RG, CPF, comprovante de residência, entre outros), armazenamento em MySQL e geração de comprovante de inscrição.

## Funcionalidades

- Validação de campos obrigatórios e formatos (CPF, CEP, telefone, renda)
- Busca automática de endereço via API ViaCEP
- Upload seguro de múltiplos documentos, renomeados com ID da inscrição
- Prevenção contra CSRF (token)
- Verificação de duplicidade por CPF, RG e NIS
- Geração de comprovante de inscrição para impressão
- Logs de erros e transações para auditoria

## Tecnologias

- PHP 8+
- MySQL / MariaDB
- JavaScript (Vanilla)
- HTML5 & CSS3
- Bootstrap (opcional)
- XAMPP ou ambiente LAMP/WAMP

## Pré-requisitos

- Servidor web Apache
- PHP 8.0 ou superior
- MySQL/MariaDB
- Composer (opcional)

## Instalação

1. Clone este repositório:
   ```bash
   git clone https://github.com/seu-usuario/ita_mcmv.git
   cd ita_mcmv
   ```
2. Copie o arquivo de ambiente:
   ```bash
   cp .env.example .env
   ```
3. Configure as credenciais de banco de dados em `.env`:
   ```ini
   DB_HOST=localhost
   DB_NAME=atrio790_assis_soci
   DB_USER=root
   DB_PASS=
   ```
4. Importe o dump SQL para criar a tabela `assistido`:
   ```bash
   mysql -u root -p atrio790_assis_soci < dump.sql
   ```

## Configuração

- **Base URL**: O arquivo `cadastro.php` calcula dinamicamente o `base_url` para que o sistema funcione em subpastas.
- **Uploads**: Arquivos são armazenados em `/uploads` e renomeados como `{id}_{campo}.{ext}`.
- **Logs**: Erros e exceções são gravados em `logs/YYYY-MM-DD.log`.

## Estrutura de Pastas

```
ita_mcmv/
├── css/             # Arquivos de estilo
├── js/              # JavaScript: validações e máscaras
├── uploads/         # Documentos enviados
├── logs/            # Arquivos de log
├── .env.example     # Exemplo de variáveis de ambiente
├── cadastro.php     # Formulário de inscrição
├── cadastrar.php    # Lógica de processamento e upload
├── comprovante.php  # Geração do comprovante
└── conectar.php     # Conexão com banco de dados
```

## Banco de Dados

- Tabela `assistido` com colunas:
  - `id` (INT AUTO_INCREMENT, PK)
  - `nome`, `cpf`, `rg`, `nis`, etc.
  - Colunas para caminhos de arquivos: `docrg`, `doccpf`, ...
  - Índices únicos em `cpf` e `rg`

## Uso

1. Acesse `http://localhost/ita_mcmv/cadastro.php`
2. Preencha todos os campos obrigatórios e anexe os documentos
3. Ao enviar, será redirecionado ao comprovante em `comprovante.php`
4. Imprima ou salve em PDF

## Logs e Debug

- Durante o desenvolvimento, ative na parte superior de `cadastrar.php`:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```
- Logs persistentes em `logs/YYYY-MM-DD.log`.

## Segurança

- Uso de token CSRF para prevenir falsificação de requisições
- Validação de MIME type e extensão nos uploads
- Sanitização de inputs antes de inserir no banco

## Contribuição

Pull requests são bem-vindos! Para grandes mudanças, abra primeiro uma issue para discutirmos o que você gostaria de modificar.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

