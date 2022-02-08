## **Pre-requirements:** 

```
php: "^8.0.2"
composer: "^2.2.4"
npm: "^14.15.4|^16.13.0"
node: "^14.18.0"
```

## **Installation** (via Sail || Docker)

### **Common**: install dependencies 
```bash
composer install
composer update
npm install 
npm run dev
```
---

### **SAIL**: install process 

Create alias for a better performance in your `.bashrc`:
Set up correct `docker-compose.yml` file
```bash 
cd DevBuildScripts/sail
sh prepare.sh
cd -
```

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```
Up docker containers:
```bash
sail up -d
```
Down docker containers with: 
```bash
sail down
```

---

### **DOCKER**: install process
Set up correct `docker-compose.yml` file
```bash 
cd DevBuildScripts/docker
sh prepare.sh
cd -
```
Up docker containers with:
```bash
docker-compose up -d
```

Down docker containers with: 
```bash
docker-compose down
```
---

### **Correct output** will be like (sail or docker), with different names: 
```
[+] Running 7/7
 ⠿ Network vibspecdb_laravel-php-nginx  Created
 ⠿ Container redis_server               Started
 ⠿ Container vibspecdb-meilisearch-1    Started
 ⠿ Container vibspecdb-rabbit-1         Started
 ⠿ Container vibspecdb-mailhog-1        Started
 ⠿ Container vibspecdb-pgsql-1          Started
 ⠿ Container vibspecdb-backend-1        Started
 ```

---

### **After installation** set-up:
Check your docker id with: 
```bash
docker ps
```

Enter into your docker container command line interface of (`vibspecdb-laravel.test-1` || `vibspecdb-backend-1`) with: 
```bash
docker exec -it **idcontainerahere** bash
```

Inside this docker container run migration and initial configuration: 
```bash
php artisan migrate:fresh
npm run dev
npx husky install
chmod +x ./.husky/pre-*
php artisan key:generate
php artisan optimize
php artisan config:clear
```
This set of commands should fix most of init problems.

---






