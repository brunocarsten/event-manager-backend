# Sistema de Gerenciamento de Evento

Este é um sistema de backend desenvolvido em **Laravel 12.x**, com **Docker** (PHP 8.2-FPM, MySQL 8 e Nginx), que gerencia o cadastro de pessoas, salas e espaços de café, fazendo a alocação automática dos participantes em duas etapas de evento e dois intervalos de café.

---

## 🛠 Tecnologias

* **PHP 8.2** (FPM)
* **Laravel 12.x**
* **MySQL 8**
* **Nginx**
* **Docker & Docker Compose**
* **Laravel Sanctum** (autenticação de API)
* **Tailwind CSS** (estilos básicos)

---

## 🚀 Pré-requisitos

* Docker (versão >= 20.10)
* Docker Compose (versão >= 1.29)
* `git`

---

## 📂 Estrutura do Projeto

```plaintext
├── app/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docker/
│   └── dev/
│       ├── default.conf     # config Nginx
│       └── docker-compose.yml
├── public/
├── resources/
├── routes/
│   └── api.php
│   └── web.php
├── storage/
├── tests/
├── .env.example
├── Dockerfile
├── README.md
└── artisan
```

---

## 📝 Variáveis de Ambiente (`.env`)

Copie o arquivo de exemplo:

```bash
cp .env.example .env
```

Ajuste conforme necessário (padrão usado):

```dotenv
APP_NAME="EventManager"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DRIVER=cookie
```

---

## 🐳 Como rodar com Docker

No diretório `docker/dev`:

1. Suba os containers e faça build:
   ```bash
   docker compose up  -d
   ```
2. Acesse o container da aplicação:
   ```bash
   docker compose exec app bash
   ```
3. As dependências são instaladas automaticamente pelo `docker`:
   Gere a base de dados `migration`
   - opcional `db:seed` para gerar registros de teste com o faker
   ```bash
   php artisan migrate
   php artisan db:seed (opcional)
   ```

4. (Opcional) Execute testes:
   ```bash
   php artisan test
   ```

A aplicação estará disponível em: **[http://localhost:8000](http://localhost:8000)**

---

## 🔧 Configuração do Nginx como Reverse Proxy
O arquivo docker/dev/default.conf configura o Nginx para funcionar como reverse proxy, encaminhando as requisições HTTP para o serviço PHP-FPM:
```
server {
    listen 80;
    server_name localhost;

    location / {
        proxy_pass         http://app:9000;
        proxy_set_header   Host $host;
        proxy_set_header   X-Real-IP $remote_addr;
        proxy_set_header   X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header   X-Forwarded-Proto $scheme;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```
---

## 📦 Endpoints Principais

### Pessoas

```
GET    /api/people        (listar)
POST   /api/people        (cadastrar)
GET    /api/people/{id}   (detalhes + alocações)
PUT    /api/people/{id}   (atualizar)
```

### Salas

```
GET    /api/rooms
POST   /api/rooms
GET    /api/rooms/{id}
PUT    /api/rooms/{id}
```

### Espaços de Café

```
GET    /api/coffee-spaces
POST   /api/coffee-spaces
GET    /api/coffee-spaces/{id}
PUT    /api/coffee-spaces/{id}
```

### Alocações

```
POST   /api/allocate                 (distribuí todos sem duplicar)
GET    /api/allocations              (agrupado por pessoa)
GET    /api/allocations/person/{id}
```

---

## 🎨 Consumo da API (via Nuxt)

No frontend Nuxt 3, configure `runtimeConfig.public.apiBase` para `http://localhost:8000/api`.
Use o composable `useApiFetch(endpoint)` para chamadas que já desembalam o wrapper `{ data: ... }`.

---

## 🧪 Testes

1. Execute:

   ```bash
   php artisan test
   ```
2. Os testes cobrem:

   * Unidade de Models e Services
   * Feature dos Endpoints de API

---

## 📑 Documentação

Este README documenta:

* Instalação e configuração
* Principais endpoints

Fique à vontade para estender com exemplos de request/response ou adicionar um arquivo OpenAPI em `docs/`.

---

## 🖋 License

Licenciado sob MIT License. Feel free to use and modify.
