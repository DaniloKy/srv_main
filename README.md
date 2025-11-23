# Survive Utopia - Web Client

Bem-vindo ao repositório do cliente web do jogo **Survive Utopia**. Este projeto foi desenvolvido em **CodeIgniter 4** e serve como a interface frontend para os jogadores.

> **Nota:** Este repositório contém apenas o Frontend (Client-side). Para veres o código do Backend (API & Websockets), acede aqui: [Link para o repo da API](https://github.com/DaniloKy/srvutp_socket)

## 📋 Pré-requisitos

Para correr este projeto localmente, precisas de ter instalado:

*   **PHP** 7.4 ou superior
*   **Composer** (Gestor de dependências PHP)
*   **MySQL** ou MariaDB (Base de dados)

## 🚀 Instalação

1.  **Clonar o repositório**
    ```bash
    git clone https://github.com/DaniloKy/srv_main.git
    cd srv_main
    ```

2.  **Instalar dependências**
    ```bash
    composer install
    ```

3.  **Configurar Ambiente**
    *   Copia o ficheiro de exemplo:
        ```bash
        cp .env.example .env
        ```
        *(No Windows: `copy .env.example .env`)*
    *   Abre o ficheiro `.env` e configura:
        *   `app.baseURL`: O URL local (ex: `http://localhost:8080/`)
        *   `database.default`: As credenciais da tua base de dados local.
        *   `SERVER_URL`: O URL da API do backend (necessário adicionar manualmente).

4.  **Base de Dados**
    *   Cria uma base de dados vazia no teu MySQL.
    *   Corre as migrações e seeds:
        ```bash
        php spark migrate
        php spark db:seed MainSeeder
        ```

## 🏃‍♂️ Como Correr

Inicia o servidor de desenvolvimento local:

```bash
php spark serve
```

Acede a `http://localhost:8080` no teu browser.

## 📂 Estrutura do Projeto

*   `app/`: Código fonte da aplicação (Controllers, Models, Views).
*   `public/`: Ficheiros públicos (CSS, JS, Imagens) e ponto de entrada (`index.php`).
*   `writable/`: Diretoria para logs, cache e uploads (precisa de permissões de escrita).
