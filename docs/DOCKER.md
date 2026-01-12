# WordPress Theme Development Environment

Ambiente Docker para desenvolvimento do tema theme-globaltech.

## Pré-requisitos

- Docker instalado e em execução (https://www.docker.com/products/docker-desktop)
- Docker Compose

## Estrutura do Projeto

```
.
├── docker-compose.yml
├── .env
├── .gitignore
└── src/                 # Tema WordPress (montado como theme-globaltech)
```

## Inicialização do Ambiente

### 1. Iniciar os containers

```bash
docker-compose up -d
```

Este comando irá:
- Provisionar os containers WordPress, MySQL e PHPMyAdmin
- Montar o diretório `./src` em `/var/www/html/wp-content/themes/theme-globaltech`
- Criar volumes persistentes para banco de dados e uploads

### 2. Acessar a aplicação

Aguarde aproximadamente 30 segundos para inicialização completa.

**WordPress**: http://localhost:8080

### 3. Instalação inicial

Execute a configuração padrão do WordPress:

1. Selecione o idioma
2. Configure credenciais administrativas
3. Complete a instalação

### 4. Ativação do tema

No painel administrativo (http://localhost:8080/wp-admin):

1. Acesse **Aparência → Temas**
2. Localize **"Theme Globaltech"**
3. Clique em **Ativar**

## Serviços Disponíveis

| Serviço | URL | Credenciais |
|---------|-----|-------------|
| WordPress | http://localhost:8080 | Definidas na instalação |
| WordPress Admin | http://localhost:8080/wp-admin | Definidas na instalação |
| PHPMyAdmin | http://localhost:8081 | root / rootpassword |

## Configuração de Ambiente

Edite o arquivo `.env` para personalizar variáveis:

```env
# Database Configuration
DB_NAME=wordpress
DB_USER=wordpress
DB_PASSWORD=wordpress
DB_ROOT_PASSWORD=rootpassword

# WordPress Configuration
WP_PORT=8080
WP_DEBUG=true

# PHPMyAdmin Configuration
PMA_PORT=8081
```

## Comandos Docker

### Gerenciamento de Containers

```bash
# Parar containers
docker-compose stop

# Reiniciar containers
docker-compose restart

# Parar e remover containers (preserva volumes)
docker-compose down

# Remover containers e volumes
docker-compose down -v

# Verificar status
docker-compose ps
```

### Logs e Debugging

```bash
# Logs de todos os serviços
docker-compose logs -f

# Logs do WordPress
docker-compose logs -f wordpress

# Logs do MySQL
docker-compose logs -f db

# Acessar shell do container WordPress
docker exec -it wordpress_app bash
```

## Estrutura Mínima do Tema

O diretório `./src` deve conter ao menos:

```
src/
├── style.css       # Obrigatório (metadata do tema)
├── index.php       # Obrigatório (template principal)
├── functions.php   # Recomendado
└── screenshot.png  # Opcional (1200x900px)
```

### Header do style.css configurado

O tema já possui o header correto configurado em [src/style.css](src/style.css):

```css
/*
Theme Name: Theme Globaltech
Theme URI: https://example.com/theme-globaltech
Author: Seu Nome
Author URI: https://example.com
Description: Tema WordPress modular e reutilizável para blogs/magazines
Version: 1.0.0
Text Domain: theme-globaltech
*/
```

## Workflow de Desenvolvimento

1. Edite arquivos em `./src`
2. Alterações são refletidas imediatamente no container
3. Recarregue o navegador para visualizar mudanças
4. Utilize PHPMyAdmin para operações de banco de dados

## Troubleshooting

### Conflito de portas

Se a porta 8080 estiver em uso, altere `WP_PORT` no arquivo `.env`:

```env
WP_PORT=8000
```

Reinicie os containers:

```bash
docker-compose down
docker-compose up -d
```

### Reset completo do ambiente

```bash
docker-compose down -v
docker-compose up -d
```

**Atenção**: Este comando remove todos os dados persistidos (banco de dados, uploads, configurações).

### Verificar logs de erro

```bash
docker-compose logs wordpress | grep -i error
docker-compose logs db | grep -i error
```

## Backup e Restore

### Exportar banco de dados

Via PHPMyAdmin (http://localhost:8081):
1. Selecione o database `wordpress`
2. Navegue até a aba "Export"
3. Execute a exportação

Via linha de comando:

```bash
docker exec wordpress_db mysqldump -u wordpress -pwordpress wordpress > backup.sql
```

### Importar banco de dados

Via PHPMyAdmin:
1. Selecione o database `wordpress`
2. Navegue até a aba "Import"
3. Selecione o arquivo SQL

Via linha de comando:

```bash
docker exec -i wordpress_db mysql -u wordpress -pwordpress wordpress < backup.sql
```

## Volumes Persistentes

O Docker Compose cria dois volumes nomeados:

- `db_data`: Dados do MySQL
- `wordpress_data`: Instalação WordPress (exceto o tema em desenvolvimento)

O tema em `./src` é montado diretamente, não está em volume.

## Arquitetura

```
┌─────────────────┐
│  WordPress App  │ :8080
│  (Container)    │
└────────┬────────┘
         │
         ├─── Volume Mount: ./src → /var/www/html/wp-content/themes/theme-globaltech
         │
         └─── Network: wordpress_network
                    │
         ┌──────────┴──────────┐
         │                     │
┌────────▼────────┐   ┌────────▼────────┐
│   MySQL DB      │   │   PHPMyAdmin    │ :8081
│  (Container)    │   │   (Container)   │
└─────────────────┘   └─────────────────┘
```

## Notas Técnicas

- O WordPress está configurado com `WORDPRESS_DEBUG=true` por padrão
- Alterações no tema são refletidas imediatamente (hot reload)
- O PHPMyAdmin facilita operações complexas de banco de dados
- Os volumes garantem persistência de dados entre reinicializações

## Documentação Adicional

- [WordPress Docker Official Image](https://hub.docker.com/_/wordpress)
- [MySQL Docker Official Image](https://hub.docker.com/_/mysql)
- [WordPress Theme Development](https://developer.wordpress.org/themes/)
