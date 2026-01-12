# Troubleshooting Guide

## ✅ Problema Resolvido: Connection Refused

### Causa do Problema

Quando movemos os arquivos para a estrutura de monorepo, o Docker Compose precisava do arquivo `.env` no diretório `docker/`, mas ele não estava lá.

### Solução Aplicada

1. **Criado `.env` no diretório correto**:
   ```bash
   docker/.env
   ```

2. **Reiniciado os containers**:
   ```bash
   cd docker
   docker-compose down -v
   docker-compose up -d
   ```

3. **Aguardado inicialização do MySQL** (~15-20 segundos)

### Como Verificar se Está Funcionando

```bash
cd docker

# 1. Verificar status dos containers
docker-compose ps

# Você deve ver:
# wordpress_app        Up      0.0.0.0:8080->80/tcp
# wordpress_db         Up      3306/tcp
# wordpress_phpmyadmin Up      0.0.0.0:8081->80/tcp

# 2. Verificar logs do MySQL
docker-compose logs db | grep "ready for connections"

# Deve mostrar: ready for connections. Version: '8.0.44'

# 3. Testar WordPress
curl -I http://localhost:8080

# Deve retornar: HTTP/1.1 302 Found
```

### Acessar o WordPress

Agora você pode acessar:

- **WordPress**: http://localhost:8080
- **WordPress Admin**: http://localhost:8080/wp-admin
- **PHPMyAdmin**: http://localhost:8081

### Volumes Montados Corretamente

```bash
# Verificar tema
docker exec wordpress_app ls /var/www/html/wp-content/themes/theme-globaltech

# Verificar plugin
docker exec wordpress_app ls /var/www/html/wp-content/plugins/theme-core-features
```

---

## Outros Problemas Comuns

### Porta 8080 em Uso

**Erro**: `Bind for 0.0.0.0:8080 failed: port is already allocated`

**Solução**:

1. Edite `docker/.env`:
   ```env
   WP_PORT=8000
   ```

2. Reinicie:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

3. Acesse: http://localhost:8000

### Plugin/Tema Não Aparecem

**Problema**: Volumes não foram montados corretamente.

**Solução**:

```bash
cd docker

# Reiniciar containers
docker-compose down
docker-compose up -d

# Verificar paths no docker-compose.yml
cat docker-compose.yml | grep -A2 volumes
```

Deve mostrar:
```yaml
volumes:
  - ../packages/theme:/var/www/html/wp-content/themes/theme-globaltech
  - ../packages/plugin:/var/www/html/wp-content/plugins/theme-core-features
```

### MySQL Demora para Inicializar

**Problema**: WordPress conecta antes do MySQL estar pronto.

**Solução**: Aguarde 15-20 segundos após `docker-compose up -d`, depois recarregue a página.

Ou adicione um healthcheck no `docker-compose.yml`:

```yaml
services:
  db:
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      timeout: 5s
      retries: 10
```

### Reset Completo do Ambiente

Se tudo mais falhar:

```bash
cd docker

# Parar e remover tudo (incluindo banco de dados)
docker-compose down -v

# Remover imagens (opcional)
docker rmi docker-wordpress mysql:8.0 phpmyadmin:latest

# Iniciar limpo
docker-compose up -d --build

# Aguardar 30 segundos
sleep 30

# Acessar
open http://localhost:8080
```

---

## Status Atual

✅ **Resolvido**: Connection refused
✅ **Containers**: Rodando corretamente
✅ **Volumes**: Montados corretamente
✅ **MySQL**: Ready for connections
✅ **WordPress**: Respondendo HTTP 302

**Próximo passo**: Acessar http://localhost:8080 e completar a instalação do WordPress!

---

## Comandos Úteis

```bash
# Ver logs em tempo real
docker-compose logs -f

# Ver logs apenas do WordPress
docker-compose logs -f wordpress

# Ver logs apenas do MySQL
docker-compose logs -f db

# Entrar no container WordPress
docker exec -it wordpress_app bash

# Entrar no container MySQL
docker exec -it wordpress_db bash

# Verificar uso de memória
docker stats

# Parar containers
docker-compose stop

# Iniciar containers
docker-compose start

# Reiniciar containers
docker-compose restart
```

---

**Última atualização**: 2026-01-12
**Status**: ✅ Funcionando
