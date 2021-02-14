<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>
<p align="center"><h2>Rest API</h2></p>

## Using Laravel 8 With Passport and Laratrust

This is a Rest API application for saving and retrieving PDF files directly into the database Mysql, encoding them with PHP's native [base64](https://www.php.net/manual/en/function.base64-encode.php) function. Using access permissions with [Laravel Laratrust](https://github.com/santigarcor/laratrust) and authentication with [Laravel Passport](https://laravel.com/docs/8.x/passport) for users. Inserting password to access the PDF file

## Installation

- System requirements
```
- PHP >= 7.3
- Composer
- Mysql
```
- Clone the repository
```
$ git clone https://github.com/torresrecife/rest_api.git
$ cd rest_api
$ cp .env.example .env
```
- Open the .env file and configure the mysql database, inserting the data of the parameters below
```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```  
- Run the commands below
```
$ composer update
$ php artisan vendor:publish --tag="laratrust"
$ php artisan migrate
$ php artisan db:seed
$ php artisan key:generate
```
- IMPORTANT: Before executing the command below, you can change the values according to your needs, in the file config/laratrust.php.
```
$ php artisan passport:install
```
- After the installation is complete, the "seed" will have created two sample users in the database. The access data are:
```
User: Admin
Email: admin@test.com
Password: 123456
Permission: admin
```
```
User: User
Email: user@test.com
Password: 123456
Permission: user
```
- Starting the server:
```
$ php artisan serve --port=80
```
## Manipulate PDF files

Both users can insert files, only in .pdf format, in the database. However, the permissions granted to users are:

- 'User' : he can only see his own files.

- 'Admin' : he will be able to see all the files.

## Running Tests using Postman

### User login:
```
Postman configuration: 

    link: http://loaclhost/api/login -> POST 
    Body -> form-data:
     - Key: email 
     - Value: user@test.com
     
     - Key: password 
     - Value: 123456
```
<img src="https://imagesgithub.s3-sa-east-1.amazonaws.com/login-user-v2.jpg">

### PDF insertion:

```
Postman configuration:

    link: http://loaclhost/api/files -> POST
     
    Header:
     - Key: Accept
     - Value: application/json
     
     - Key: Authorization
     - Value: "obtained token"
    
    Body -> form-data:
     - Key: name
     - Value: "Choose a name for the file"
     
     - Key: description
     - Value: "Choose a brief description for the file"
     
     - Key: content
     - Value: "change the field to 'file' and locate the .pdf file on your computer".
     
     - Key: password
     - Value: "enter a password for the pdf".
```
<img src="https://imagesgithub.s3-sa-east-1.amazonaws.com/insert-pdf-user-v2.jpg">

### PDF view list:



```
Postman configuration:

    link: http://loaclhost/api/files -> GET
    
    Header:
     - Key: Accept
     - Value: application/json
     
     - Key: Authorization
     - Value: "obtained token"
```

<h6>Attention: Only the "admin" user can view all files. The user "user" will only see your files.
Note that at that moment only information about the file will be displayed. The contents of the file will be protected by password and will be on another link.</h6>

#### View user:

<img src="https://imagesgithub.s3-sa-east-1.amazonaws.com/view-user-v2.jpg">

#### View admin:

<img src="https://imagesgithub.s3-sa-east-1.amazonaws.com/view-admin-v2.jpg">

### PDF view detail:

#### The 'user' views the contents of the pdf file using the password:

- Viewing the file contents using the file password

<h6>Attention: A character limiter has been inserted in the output of the "content" of the file data, for better visualization in Postman.
It should be removed from the file: app/Http/Resources/Files.php: line 24</h6>

<img src="https://imagesgithub.s3-sa-east-1.amazonaws.com/viewing-allowed-password-user-v2.jpg">

- If the user tries to view another file that does not have permission, even if he has the password, the file will not be displayed.

<img src="https://imagesgithub.s3-sa-east-1.amazonaws.com/blocked-view-password-user-v2.jpg">

## Packages used
 - Laravel 8:
   
    https://laravel.com/docs/8.x
 - Laravel Laratrust:
   
    https://github.com/santigarcor/laratrust
 - Laravel Passport:
   
   https://laravel.com/docs/8.x/passport.
