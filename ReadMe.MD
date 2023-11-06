### 1. clone project `git clone git@github.com:asimonyan/test.git`

### 2. go to workdir

### 3. install vendors `composer install`

### 4. Run docker `docker compose up --build -d `

### 5. Init database/and data do db run `docker exec php-test bash init.sh`

### 6.open http://127.0.0.1:8080/

ps: (с Докером(всегда настроивал свое окружение сам) и с PostgreSql(работал всегда с MySql) работал первый раз)