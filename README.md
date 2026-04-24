# php-contact-list

Agenda de contatos com CRUD completo desenvolvida em PHP puro + MySQL. Atividade prática da disciplina de Redes de Computadores II — IFRO.

---

## Pré-requisitos

- Debian (ou derivado) com stack LAMP instalada:
  - **Apache2**
  - **PHP** (7.4 ou superior)
  - **MySQL** (5.7 ou superior)
- Acesso root ou sudo na VM

---

## Instalação

### 1. Clonar o repositório

```bash
cd /var/www/html
git clone https://github.com/Guilherme-Tavares/php-contact-list.git agenda
```

### 2. Criar o banco de dados

Acesse o MySQL e execute o script de schema:

```bash
mysql -u root -p < /var/www/html/agenda/sql/schema.sql
```

Ou pelo MySQL Workbench: abra o arquivo `sql/schema.sql` e execute.

### 3. Configurar a conexão

Edite o arquivo `config/db.php` com as credenciais do seu ambiente:

```php
$host     = 'localhost';
$user     = 'root';
$password = 'sua_senha';   // ajustar conforme a VM
$database = 'contact_list';
```

### 4. Ajustar permissões do diretório (se necessário)

```bash
sudo chown -R www-data:www-data /var/www/html/agenda
sudo chmod -R 755 /var/www/html/agenda
```

### 5. Acessar no navegador

Abra o navegador e acesse:

```
http://localhost/agenda/
```

---

## Funcionalidades

| Operação | URL |
|---|---|
| Listar contatos | `index.php` |
| Cadastrar contato | `create.php` |
| Ver detalhe | `view.php?id={id}` |
| Editar contato | `edit.php?id={id}` |
| Excluir contato | `delete.php?id={id}` |

---

## Estrutura de arquivos

```
php-contact-list/
├── index.php
├── create.php
├── edit.php
├── delete.php
├── view.php
├── config/
│   └── db.php
├── assets/
│   ├── css/style.css
│   └── js/main.js
├── sql/
│   └── schema.sql
└── README.md
```
