# Queue Management

Queue Management is a online reserve queue for any activity at the bank.

- - - -

### Design  ###

#### Database ####

[x] **roles**

```sh
id AI PK
name string (40)
description string (255)
timestamp
```

[x] **users**

```sh
id AI PK
username string (30) unique
password string (200)
name string (100)
email string (100)
phone string (10)
ip string (20)
remember_token string (100)
timestamp
```

#### View ####

[ ] **index**

```
* 3 Tabs
 * 1 active queues
  * queue name
  * opentime
  * count
  * remaining time
  * <button> show info

 * 2 user's reserved queues
  * queue name
  * queue time
  * remaining time to get service
  * captcha code
  * <button> show info

 * 3 future queues
  * queue name
  * opentime
  * remaining time
  * <button> show info
```

[ ] **Login & Register**

```
**Register requirement**
username
password
email
name , surname
phone
```

- - - -

### Features
  - Authentication & Authorization.
  - Create the queue
  - Reserve the queue
  - Admin can manage users,queues
  - Check user for receiver service system

- - - -

### Tech

Queue Management uses a number of open source projects to work properly.

* [Laravel] - The PHP Framework For Web Artisans
* [Materialize CSS] - a modern CSS framework based on Flexbox.
* [Gulp] - the streaming build system
* [jQuery]

And of course Queue Managerment itself is open source with a [public repository][projectx]
 on GitHub.

- - - -

### Installation

Ez-Quiz requires [Laravel](https://laravel.com/docs/5.2/) v5.2 to run.

Install with composer.


```sh
$ composer install
```

For production environments.

> rename .env.example to .env and edit.
> .env will protected with .htaccess.

```sh
APP_ENV=production
APP_DEBUG=false
DB_HOST=localhost
DB_DATABASE= [YOUR Database name]
DB_USERNAME= [YOUR Database username]
DB_PASSWORD = [YOUR Database password]
```

deploy key & database.

```sh
php artisan key:generate
php artisan migrate
```

### License

The Ez-Quiz is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

   [projectx]: <https://github.com/pozterz/Project_X>
   [git-repo-url]: <https://github.com/pozterz/Project_X.git>
   [Laravel]: <https://laravel.com/docs/5.2/>
   [Materialize CSS]: <http://materializecss.com/>
   [jQuery]: <http://jquery.com>
   [Gulp]: <http://gulpjs.com>

