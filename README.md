# laravel-tutorials
Official Laravel 5.2 tutorials with task list app.

## Installation ##
Using Laravel 7.
https://laravel.com/docs/7.x

## Setup ##
> composer create-project laravel/laravel task-list-app --prefer-dist
> cd task-list-app

## Database ##
Create table/s using **migration** and **migrate**.
**make:migration** command to generate a new database migration for **tasks** table.
> php artisan make:migration create_tasks_table --create=tasks

Add string **name** in date_create_tasks_table.php.
```
public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name'); //new code
        $table->timestamps();
    });
}
```

**Note**: Make sure **lamp** or **xampp** is running. You will know if it's running when you can access **localhost/phpmyadmin/**.

**migrate** command will create all of databases tables.
> php artisan migrate

Check your table/s from your database(default db 'laravel' were used in this tutorial) in **localhost/phpmyadmin/**.