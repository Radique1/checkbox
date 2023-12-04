## Installation
Build application
```bash
1. docker-compose up -d --build
```
Enter php container
```bash
2. docker exec -it checkbox_php bash
```
Install dependencies
```bash
3. composer install
```
Create database
```bash
4. bin/console doctrine:database:create
```
Execute migrations
```bash
5. bin/console doctrine:migrations:migrate
```
Generate keypair for JWT 
```bash
6. bin/console lexik:jwt:generate-keypair
```
Create user
```bash
7. bin/console app:create-user
```

## Swagger documentation: 
```localhost/docs```