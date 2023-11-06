How to install
====

### Step1: Clone the project
```ssh
git clone git@github.com:asimonyan/test.git
```

### Step2: Go to workdir

### Step3: install vendors
```ssh
composer install
```

### Step4: Run docker
```ssh
docker compose up --build -d 
```

### Step5: Init database/and data do db run
```ssh
docker exec php-test bash init.sh
```
### Step6: open http://127.0.0.1:8080/

ps: С Докером(всегда настроивал свое окружение сам) и с PostgreSql(работал всегда с MySql) работал первый раз

