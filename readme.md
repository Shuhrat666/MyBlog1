# To start with, clone the project from git:
```bash
git clone https://github.com/Shuhrat666/MyBlog1.git

```
# password.example.php must be copied to password.php file and and neccessary data must be entered to the file:
```bash
cp password.example.php password.php 
```
# During next step, mig31.php in 'migrations' directory is executed:
```bash
php mig31.php
```
# Having run migrations, it is time to run the project:
```bash
php -S localhost:8000
```
# Instructions:
Having checked by 'Login' page, registered users can search and view others' posts, leave comments, add their own posts alongside with editing and deleting them. 
# Requirements:
PHP 8.1.2
