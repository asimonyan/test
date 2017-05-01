How to install
====

### Step1: Clone the project
```ssh

git clone git@github.com:asimonyan/test.git
```

### Step2: Update composer for backend dependency
#### Do not change 'captcha_key' and 'captcha_security' in parameters.yml
### Step3: create database and update schema
```ssh
app/console doctrine:database:create
app/console doctrine:schema:update --force
```
### Step4: Install bower for frontend dependency
```ssh
bower install
```
### Step5: Add permission

```ssh
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1` &&
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache/ app/logs &&
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache/ app/logs
```

### Step6: Create virtual host
#### host name must be 'localtest.com'. This is important for google re-captcha

### Step7: Create user with SUPER_USER_ADMIN role, for admin panel