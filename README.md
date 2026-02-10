<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

## Backend API

API REST desenvolvida em **Laravel**, projeto utiliza **Docker**, **MySQL** e segue boas práticas de arquitetura, aplicando conceitos de **SOLID**, **DRY** e testes automatizados.

---

## Stack utilizada

- PHP 8.4
- Laravel 12
- MySQL 8
- Nginx
- Docker e Docker Compose
- JWT (JSON Web Token)
- PHPUnit

---

## Pré-requisitos

- Docker
- Docker Compose

> Não é necessário ter PHP ou Composer instalados localmente.

---

## Instalação e execução

### Clonar o repositório

```bash
git clone https://github.com/jonas-amilton/targetit-api.git
cd targetit-api
```

---

### Configurar variáveis de ambiente

```bash
cp .env.example .env
```

O arquivo `.env.example` já está configurado para execução em ambiente Docker local.

---

### Subir os containers

```bash
docker compose up -d --build
```

---

### Instalar dependências e preparar a aplicação

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

---

### Acessar a aplicação

- API disponível em: **http://localhost:8080**

---

## Executar testes

```bash
docker compose exec app php artisan test
```

---

## Endpoints da API

### Autenticação

**Registrar usuário:**
```http
POST /api/auth/users
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "phone": "11999999999",
  "cpf": "12345678901",
  "password": "password123"
}
```

**Login:**
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "joao@example.com",
  "password": "password123"
}
```

**Obter dados do usuário autenticado:**
```http
GET /api/auth/me
Authorization: Bearer {token}
```

**Logout:**
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

---

### Usuários (Requer autenticação)

**Listar usuários:**
```http
GET /api/auth/users
Authorization: Bearer {token}
```

**Obter usuário específico:**
```http
GET /api/auth/users/{id}
Authorization: Bearer {token}
```

**Atualizar usuário:**
```http
PUT /api/auth/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "João Silva Updated",
  "email": "novo@example.com",
  "phone": "11988888888",
  "cpf": "98765432101"
}
```

**Deletar usuário (soft delete):**
```http
DELETE /api/auth/users/{id}
Authorization: Bearer {token}
```

---

### Endereços (Requer autenticação)

**Listar endereços do usuário:**
```http
GET /api/auth/users/{user_id}/addresses
Authorization: Bearer {token}
```

**Criar endereço:**
```http
POST /api/auth/users/{user_id}/addresses
Authorization: Bearer {token}
Content-Type: application/json

{
  "street": "Rua Principal",
  "number": "123",
  "district": "Centro",
  "cep": "12345678",
  "complement": "Apto 101"
}
```

**Obter endereço específico:**
```http
GET /api/auth/users/{user_id}/addresses/{address_id}
Authorization: Bearer {token}
```

**Atualizar endereço:**
```http
PUT /api/auth/users/{user_id}/addresses/{address_id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "street": "Rua Atualizada",
  "number": "456",
  "district": "Zona Sul",
  "cep": "87654321",
  "complement": "Apto 202"
}
```

**Deletar endereço:**
```http
DELETE /api/auth/users/{user_id}/addresses/{address_id}
Authorization: Bearer {token}
```

---

### Permissões (Requer autenticação)

**Atribuir permissão a usuário:**
```http
POST /api/auth/users/{user_id}/permissions
Authorization: Bearer {token}
Content-Type: application/json

{
  "id": 1
}
```

**Listar permissões do usuário:**
```http
GET /api/auth/users/{user_id}/permissions
Authorization: Bearer {token}
```

**Remover permissão do usuário:**
```http
DELETE /api/auth/users/{user_id}/permissions/{permission_id}
Authorization: Bearer {token}
```

---

## Testando com Postman

Uma **collection do Postman** está disponível no diretório `docs/` para facilitar os testes da API.

### Importar a collection:

1. Abra o Postman
2. Clique em **"Import"**
3. Selecione o arquivo `docs/TargetIT-API.postman_collection.json`
4. A collection "TargetIT API" será importada com todos os endpoints configurados

### Configuração:

- A variável `base_url` já está configurada para `http://localhost:8080`
- Após fazer login, o token JWT é automaticamente salvo e usado nas requisições protegidas

### Fluxo recomendado de testes:

1. **Registrar usuário** (POST /api/auth/users) - Cria um novo usuário
2. **Login** (POST /api/auth/login) - Autentica e retorna o token JWT
3. **Me** (GET /api/auth/me) - Valida o token e retorna dados do usuário autenticado
4. Testar os demais endpoints de **Usuários**, **Endereços** e **Permissões**

---

## Autenticação

A autenticação é realizada via **JWT**.

O token deve ser enviado no header das requisições protegidas:

```http
Authorization: Bearer {token}
```

---

## Funcionalidades

- Autenticação JWT
- CRUD de usuários
- Soft delete de usuários
- Associação de permissões
- Cadastro de endereços por usuário
- Testes automatizados de validação

---

## Organização do projeto

O projeto segue uma arquitetura em camadas:

- Controllers: entrada da aplicação
- Services: regras de negócio
- Repositories: acesso a dados
- DTOs: transporte de dados
- Form Requests: validação
- Tests: testes unitários e de integração

Essa estrutura facilita manutenção, testes e evolução do sistema.

---

## Observações

- As credenciais presentes no `.env.example` são exclusivas para ambiente local Docker.
- O arquivo `.env.example` não contém o valor de `JWT_SECRET` por segurança.
- O segredo JWT é gerado automaticamente com o comando `php artisan jwt:secret`.
- Os dados iniciais (usuários e permissões) são populados automaticamente via seeders.

---

docs/
└── TargetIT-API.postman_collection.json  (Collection do Postman)

## Estrutura de Diretórios

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── User/              (Controladores invocáveis de usuário)
│   │       ├── Address/           (Controladores invocáveis de endereço)
│   │       ├── Permission/        (Controladores invocáveis de permissão)
│   │       └── AuthController.php
│   └── Requests/                  (Form Requests com validações)
├── Models/                         (User, Address, Permission)
├── Services/                       (UserService, AddressService)
├── Repositories/
│   ├── UserRepository.php
│   ├── AddressRepository.php
│   └── Contracts/                 (Interfaces dos repositórios)
└── DTOs/                           (UserDTO, AddressDTO)

database/
├── migrations/
├── seeders/                        (UserSeeder, PermissionSeeder, AddressSeeder)
└── factories/

tests/
├── Unit/                           (11 testes de serviços e DTOs)
├── Feature/                        (21 testes de APIs)
└── Integration/                    (10 testes end-to-end)
```

---

## Princípios de Arquitetura

Este projeto implementa os 5 princípios **SOLID**:

- **S**: Single Responsibility - Cada classe tem uma única responsabilidade
- **O**: Open/Closed - Aberto para extensão, fechado para modificação
- **L**: Liskov Substitution - Interfaces permitem substituição de implementações
- **I**: Interface Segregation - Interfaces específicas e bem definidas
- **D**: Dependency Inversion - Dependências em abstrações, não em implementações

Adicionalmente:
- **DRY** (Don't Repeat Yourself) - Código reutilizável
- **Service-Repository Pattern** - Separação de responsabilidades
- **DTOs** - Transfer Objects para validação de dados
- **Type Hints** - 100% do código tipado
