# Gerenciador de Cidades - Teste Técnico 

Este projeto é um sistema de gerenciamento de Cidades e Unidades Federativas (UFs) desenvolvido em *Laravel 11*. A aplicação permite o cadastro automatizado de cidades através da integração com APIs externas, garantindo a integridade dos dados.

## Tecnologias Utilizadas

- *Framework:* Laravel 11
- *Ambiente:* Laravel Sail (Docker)
- *Banco de Dados:* PostgreSQL
- *Linguagem:* PHP 8.5.5 
- *Frontend:* Vue.js 3 (Composition API) & Tailwind CSS

## 🛠 Pré-requisitos
você precisará ter instalado em sua máquina:
- **Docker Engine**: O motor que gerencia os containers.
- **Docker Compose**: Para orquestrar os serviços.
- **Git**: Para clonagem e versionamento.
- **WSL2 (Windows)**: Para rodar o ambiente Linux/Docker.

## Endpoints Principais (API)

### Cidades:
**GET** `/api/cities`: Lista cidades de forma paginada.
Parâmetros: `s` (busca por nome/IBGE), `uf_id` (filtro por estado) e `page`.

**POST** `/api/cities`: Cadastra uma nova cidade.
Realiza validação cruzada entre CEP e IBGE via APIs externas.

**GET** `/api/cities/{id}`: Retorna os dados detalhados de uma cidade específica.

**PUT** `/api/cities/{id}`: Atualiza os dados de uma cidade existente.

**DELETE** `/api/cities/{id}`: Remove o registro da cidade.

### Estados (UFs):
**GET** `/api/ufs`: Lista estados de forma paginada ou completa.
Parâmetros: `s` (busca por nome/sigla) e `page`.

**POST** `/api/ufs`: Cadastra uma nova UF.
Campos: Nome do estado e sigla (`state_code`).

**GET** `/api/ufs/{id}`: Retorna os dados detalhados de um estado específico.

**PUT** `/api/ufs/{id}`: Atualiza os dados de um estado existente.

**DELETE** `/api/ufs/{id}`: Remove o registro do estado.

## Como Instalar e Rodar

Siga os passos abaixo para subir o ambiente:

### 1. Clonar o repositório
`git clone https://github.com/Pedro-Eugelmi/laravel-crud-cities.git `

### 2. Entrar na pasta 
`cd laravel-crud-cities`

### 3. Criar o arquivo de ambiente
`cp .env.example .env`

### 4. Instalar as dependências
`docker run --rm -v "$(pwd):/opt" -w /opt laravelsail/php85-composer:latest composer install`
    
### 5. Iniciar o sail
`./vendor/bin/sail up -d`

### 6. Gerar a chave de segurança
`./vendor/bin/sail artisan key:generate `

### 7. Criar as tabelas e popular o banco
`./vendor/bin/sail artisan migrate:fresh --seed `

### 8. Instalar dependências do Frontend
`./vendor/bin/sail npm install `

### 9. Rodar o Vite
`./vendor/bin/sail npm run dev`

### 10. Acessar a aplicação
A aplicação estará disponível em: [http://localhost](http://localhost)