# README #

Alundra System is a simple task manager, based on projects that require employees to always test their tools and have a simple and fast communication for each part of the project.

Use time tracking to better enjoy every part of the creation process.

### General info ###

* Date : 10/07/2014
* Version : 1.00 beta

### Requirements ###
+ PHP server (min: 5.4)
* PHP Drivers: mysql,mysqli,mysqlnd, PDO
+ MySQL server ( min: 5.6 )
+ Apache
+ Browser with support HTML5 and CSS3

### Features ###
* Responsive website
* MVC architeture
* AJAX (dynamic) website
* Login with captcha!
* 
* Front End: Javascript (with Jquery), HTML5, CSS3 (With Bootstrap)
* Back End: PHP, MySQL

### Setup ###

1. Download/clone this repository
2. Put "_public-html" at your server
3. Use the files at "_database" to create tables and populate them at your MySql Server
4. Edit file at "_public-html > _commons > _php > ValidateQueriesMc" . change the fields:   
 * $dbServer   with your MySql server address;
 * $dbUsername with your MySql server username;
 * $dbPassword with your MySql server password;
 * $dbName     with your MySql server database with Alundra Tables ( normally: dbAlundraSystem ) ;
5. Use =]

### Screns ###

![](https://i.imgur.com/fah7nUo.png)
![](https://i.imgur.com/uPDthi2.png)
![](https://i.imgur.com/iaVfzq8.png)

### USE TERMS ###
(based on MIT License)

 Copyright (c) 2014 Elton Martins, Marcelo Belkiman

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 Should never be removed or discredited the main creators (Marcelo Belkiman, Elton Martins).
 ALWAYS must contain a header on each page the name of the creators of Alundra System.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.