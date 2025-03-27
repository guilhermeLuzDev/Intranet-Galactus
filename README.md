# Documentação do Sistema - Grupo Galactus

## 1. Visão Geral
Este sistema web, desenvolvido pela equipe de TI do Grupo Galactus, é uma plataforma interna para gerenciamento de ramais telefônicos e acesso a recursos corporativos. Inclui uma página inicial com links para ferramentas, um diretório de ramais com cadastro, edição e exclusão, e uma seção para planilhas corporativas. O acesso a funcionalidades de gestão é restrito a usuários autenticados ("admin" ou "usuario").

**Objetivo**: Facilitar a gestão de contatos internos e o acesso a recursos corporativos.

**Data da Documentação**: 27 de março de 2025.

## 2. Arquivos do Sistema

### 2.1. `index.php`
- **Descrição**: Página inicial pública com links para ferramentas corporativas.
- **Funcionalidades**: Saudação dinâmica, data/hora em tempo real, cards interativos.
- **Dependências**: Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto, Montserrat).
- **Acesso**: Público.

### 2.2. `agenda.php`
- **Descrição**: Lista ramais com filtros e ações para usuários logados.
- **Funcionalidades**: Filtros, tabela de ramais, botões "Editar", "Excluir", "Criar Novo Contato". Inclui logout por inatividade.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Público (visualização), restrito para ações.

### 2.3. `cadastro.php`
- **Descrição**: Formulário para cadastro de novos ramais.
- **Funcionalidades**: Campos obrigatórios, validação de email e ramal duplicado, inserção na tabela `ramal`. Inclui logout por inatividade.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Restrito (admin ou usuario).

### 2.4. `loginti.php`
- **Descrição**: Página de login para o setor de TI.
- **Funcionalidades**: Autenticação, limite de 10 tentativas, mensagem de timeout.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Público (antes do login).

### 2.5. `logout.php`
- **Descrição**: Encerra a sessão.
- **Funcionalidades**: Destrói sessão e redireciona para `loginti.php`.
- **Dependências**: PHP.
- **Acesso**: Restrito.

### 2.6. `editar.php`
- **Descrição**: Formulário para edição de ramais.
- **Funcionalidades**: Busca por ID, validação de email e ramal duplicado, atualização na tabela `ramal`. Inclui logout por inatividade.
- **Dependências**: PDO (MySQL), Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Restrito (admin ou usuario).

### 2.7. `excluir.php`
- **Descrição**: Exclui ramais.
- **Funcionalidades**: Remove registro da tabela `ramal` por ID. Inclui logout por inatividade.
- **Dependências**: PDO (MySQL).
- **Acesso**: Restrito.

### 2.8. `planilhas.php`
- **Descrição**: Links para planilhas corporativas.
- **Funcionalidades**: Lista de links para arquivos Excel. Inclui logout por inatividade.
- **Dependências**: Bootstrap 5.3, Bootstrap Icons, Google Fonts (Roboto).
- **Acesso**: Público (visualização), exibe status de login.

### 2.9. `keep_alive.php`
- **Descrição**: Mantém a sessão ativa.
- **Funcionalidades**: Atualiza o timestamp de última atividade via requisições AJAX.
- **Dependências**: PHP.
- **Acesso**: Restrito.

## 3. Banco de Dados
- **Nome**: `ramal_db`
- **Tabelas**:
  1. **`ramal`**:
     - `id` (int, auto_increment, primary key)
     - `ramal` (varchar)
     - `usuario` (varchar)
     - `email` (varchar)
     - `setor` (varchar)
     - `escritorio` (varchar)
  2. **`usuario`**:
     - `id` (int, auto_increment, primary key)
     - `usuario` (varchar)
     - `senha` (varchar)
     - `role` (varchar)

## 4. Dependências
- **PHP**: 7.0+ com PDO.
- **MySQL**: Banco `ramal_db`.
- **Bibliotecas**: Bootstrap 5.3, Bootstrap Icons 1.11.3, Google Fonts (Roboto, Montserrat).
- **Arquivos Locais**: Imagens (`logogalactus.jpg`, `logosemfundo.png`), pasta `planilhas/`.

## 5. Instruções de Instalação
1. Configure um servidor web com PHP e MySQL.
2. Crie o banco `ramal_db` e as tabelas `ramal` e `usuario`.
3. Copie os arquivos PHP para o diretório do servidor.
4. Ajuste as credenciais do banco nos arquivos (atualmente "root" sem senha).
5. Acesse `index.php` ou `loginti.php`.

## 6. Fluxo de Uso
1. Acessar `index.php` para recursos.
2. Login em `loginti.php`.
3. Gerenciar ramais em `agenda.php`.
4. Acessar planilhas em `planilhas.php`.
5. Logout automático após 15 minutos de inatividade ou manual em `logout.php`.

## 7. Notas Técnicas
- **Segurança**: Senhas em texto puro no banco `usuario` são um risco. Recomenda-se usar `password_hash()` e `password_verify()`.
- **Erro 404**: Links em `index.php` (ex.: GLPI) devem ser testados.
- **Melhorias Implementadas**:
  - Logout automático após 15 minutos de inatividade.
  - Validação de ramais duplicados em `cadastro.php` e `editar.php`.
