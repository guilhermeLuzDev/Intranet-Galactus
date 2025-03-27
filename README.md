# Documentação do Sistema - Grupo Galactus

## 1. Visão Geral

### Objetivo
Facilitar a gestão de contatos internos e o acesso a recursos corporativos.

### Público-Alvo
Funcionários do Grupo Galactus, com funcionalidades restritas a administradores e usuários autenticados.

### Benefícios
Centraliza ramais telefônicos, agiliza o acesso a recursos e melhora a eficiência operacional.

**Data da Documentação**: 27 de março de 2025.

---

## 2. Arquivos do Sistema

### 2.1. `index.php`
- **Descrição**: Página inicial pública com links para ferramentas corporativas.
- **Funcionalidades**: Saudação dinâmica, data/hora em tempo real, cards interativos com links.
- **Dependências**: Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto, Montserrat).
- **Acesso**: Público.
- **Integrações**: Links redirecionam para ferramentas externas (ex.: GLPI).

### 2.2. `agenda.php`
- **Descrição**: Lista ramais com filtros e ações para usuários logados.
- **Funcionalidades**: Filtros por setor ou usuário, tabela de ramais, botões "Editar", "Excluir" e "Criar Novo Contato", logout por inatividade após 15 minutos.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Visualização pública; ações restritas a usuários autenticados (admin ou usuario).
- **Integrações**: Usa `cadastro.php` para novos ramais, `editar.php` para atualizações e `excluir.php` para remoções.

### 2.3. `cadastro.php`
- **Descrição**: Formulário para cadastro de novos ramais.
- **Funcionalidades**: Campos obrigatórios, validação de email e ramal duplicado, inserção na tabela `ramal`, logout por inatividade após 15 minutos.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Restrito (admin ou usuario).
- **Integrações**: Dados inseridos aparecem em `agenda.php`.

### 2.4. `loginti.php`
- **Descrição**: Página de login para o setor de TI.
- **Funcionalidades**: Autenticação, limite de 10 tentativas, mensagem de timeout após falhas.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Público (antes do login).
- **Integrações**: Redireciona para `agenda.php` após sucesso.

### 2.5. `logout.php`
- **Descrição**: Encerra a sessão do usuário.
- **Funcionalidades**: Destrói a sessão e redireciona para `loginti.php`.
- **Dependências**: PHP.
- **Acesso**: Restrito.
- **Integrações**: Chamado por outros arquivos após inatividade.

### 2.6. `editar.php`
- **Descrição**: Formulário para edição de ramais existentes.
- **Funcionamentos**: Busca por ID, validação de email e ramal duplicado, atualização na tabela `ramal`, logout por inatividade após 15 minutos.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Restrito (admin ou usuario).
- **Integrações**: Atualizações refletem em `agenda.php`.

### 2.7. `excluir.php`
- **Descrição**: Exclui ramais da base de dados.
- **Funcionalidades**: Remove registro da tabela `ramal` por ID, logout por inatividade após 15 minutos.
- **Dependências**: PDO (MySQL).
- **Acesso**: Restrito (admin ou usuario).
- **Integrações**: Alterações aparecem em `agenda.php`.

### 2.8. `planilhas.php`
- **Descrição**: Links para planilhas corporativas.
- **Funcionalidades**: Lista de links para arquivos Excel, logout por inatividade após 15 minutos.
- **Dependências**: Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Público (visualização); exibe status de login.
- **Integrações**: Links apontam para arquivos na pasta `planilhas/`.

### 2.9. `keep_alive.php`
- **Descrição**: Mantém a sessão ativa.
- **Funcionalidades**: Atualiza o timestamp de última atividade via requisições AJAX.
- **Dependências**: PHP.
- **Acesso**: Restrito.
- **Integrações**: Usado por páginas autenticadas para evitar logout.

---

## 3. Banco de Dados

**Nome**: `ramal_db`

### Tabelas

#### `ramal`
| Coluna      | Tipo                | Restrições           |
|-------------|---------------------|----------------------|
| `id`        | INT                 | AUTO_INCREMENT, PRIMARY KEY |
| `ramal`     | VARCHAR(10)         | NOT NULL, UNIQUE     |
| `usuario`   | VARCHAR(50)         | NOT NULL             |
| `email`     | VARCHAR(100)        | NOT NULL, UNIQUE     |
| `setor`     | VARCHAR(50)         |                      |
| `escritorio`| VARCHAR(50)         |                      |

#### `usuario`
| Coluna      | Tipo                | Restrições           |
|-------------|---------------------|----------------------|
| `id`        | INT                 | AUTO_INCREMENT, PRIMARY KEY |
| `usuario`   | VARCHAR(50)         | NOT NULL, UNIQUE     |
| `senha`     | VARCHAR(255)        | NOT NULL             |
| `role`      | ENUM('admin', 'usuario') | NOT NULL        |

**Relacionamentos**: Nenhum explícito, mas `ramal.usuario` pode corresponder informalmente a `usuario.usuario`.

---

## 4. Dependências

- **PHP**: 7.4+ com extensão PDO habilitada.
- **MySQL**: 5.7+ com banco `ramal_db`.
- **Bibliotecas**:
  - Bootstrap 5.3.0 (via CDN ou `npm install bootstrap@5.3.0`)
  - Bootstrap Icons 1.11.3 (via CDN)
  - Google Fonts: Roboto e Montserrat (via `<link>`).
- **Arquivos Locais**: `logogalactus.jpg`, `logosemfundo.png`, pasta `planilhas/`.

---

## 5. Instruções de Instalação

1. Configure um servidor web (ex.: Apache) com PHP 7.4+ e MySQL 5.7+.
2. Crie o banco de dados e tabelas:
   ```sql
   CREATE DATABASE ramal_db;
   USE ramal_db;
   CREATE TABLE ramal (
       id INT AUTO_INCREMENT PRIMARY KEY,
       ramal VARCHAR(10) NOT NULL UNIQUE,
       usuario VARCHAR(50) NOT NULL,
       email VARCHAR(100) NOT NULL UNIQUE,
       setor VARCHAR(50),
       escritorio VARCHAR(50)
   );
   CREATE TABLE usuario (
       id INT AUTO_INCREMENT PRIMARY KEY,
       usuario VARCHAR(50) NOT NULL UNIQUE,
       senha VARCHAR(255) NOT NULL,
       role ENUM('admin', 'usuario') NOT NULL
   );
