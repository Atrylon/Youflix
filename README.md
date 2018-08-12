#YOUFLIX

**Symfony course projet :**

A Website beetween Youtube and Netflix to save URLs of your favorite videos.

##Getting Started

###Installing
The first thing to do is to launch the server : ``php bin/console server:run``

Next you will need a database :
* You can use the export  **youflix.sql** to start with some users and videos examples
* Or if you prefer, you can start with a clean database. For this, you have to create a 
new DB, update the database URL in the **.env file** and finally use : ``php bin/console d:s:u -force``

You can now register, login and save your videos !

###Commands

Two commands are available is this project:
* You can create an admin while using ``php bin/console app:create-admin EMAIL PASSWORD``
* You can count the number of videos of a User by using : ``php bin/console app:user-count-videos EMAIL``

###Built With
* [Symfony 4](https://symfony.com/4) - The Web framework used
* [Bootstrap 4](https://getbootstrap.com) - The Design toolkit
* [PhpMyAdmin](https://www.phpmyadmin.net/) - The software for the Database

##Author
**Berenger Desgardin** - *Initial work* - [Atrylon](https://gihub.com/Atrylon)
