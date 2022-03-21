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
git config --global core.autocrlf false
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
npm install husky --save-dev
npx husky install
npm set-script prepare "husky install"
npx husky add .husky/pre-commit "npm test"
chmod +x ./.husky/pre-*
php artisan key:generate
php artisan optimize
php artisan config:clear
php artisan route:clear 
```
This set of commands should fix most of init problems.

---
### Lerna shared vue components

Make sure that commands `npm i` and `composer install` are done. 

```bash
npm i
npm run lerna
```
After these commands you may run to have online changes in browser:
```bash
npm run hot
```
You can develop shared vue modules, and push changes to your git sub-modules. 

---

### Needed Lerna structure (already done for this repo): 

- created folder `/resourses/js/pakages`
- git repo  with `serviceappvuecomponents` cloned 
- this repo was added as git submodule to the laravel repo
- `lerna.json` config file added 
- `package.json` new npm command added

---





