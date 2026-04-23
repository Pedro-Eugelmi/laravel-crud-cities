# Gerenciador de Cidades - Teste Técnico (trabalho em progresso)

Este projeto é um sistema de gerenciamento de Cidades e Unidades Federativas (UFs) desenvolvido em **Laravel 11**. A aplicação permite o cadastro automatizado de cidades através da integração com APIs externas, garantindo a integridade dos dados.

## Tecnologias Utilizadas

- **Framework:** Laravel 11
- **Ambiente:** Laravel Sail (Docker)
- **Banco de Dados:** PostgreSQL
- **Linguagem:** PHP 8.5.5 

## Como Instalar e Rodar

Siga os passos abaixo para subir o ambiente:

### 1. Clonar o repositório
`git clone https://github.com/Pedro-Eugelmi/laravel-crud-cities.git `

### 2. Entrar na pasta 
`cd laravel-crud-cities`

### 3. Criar o arquivo de ambiente
`cp .env.example .env`

### 4. Instalar as dependências
`docker run --rm \`
`-v $(pwd):/opt \`
`-w /opt \`
`laravelsail/php85-composer:latest \`
`composer install`
    
### 5. Iniciar o sail
`./vendor/bin/sail up -d`

### 6. Gerar a chave de segurança
`./vendor/bin/sail artisan key:generate` 

### 7. Criar as tabelas e popular o banco
`./vendor/bin/sail artisan migrate:fresh --seed` 
