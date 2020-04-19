# laravel-tutorials
Official Laravel 5.2 tutorials with Intermediate Task List app.

## Installation
Using Laravel 7. https://laravel.com/docs/7.x

## Setup
> composer create-project laravel/laravel task-list-app --prefer-dist


## Database
### Create table/s using migration and migrate.
**make:migration** command to generate a new database migration for **tasks** table.
> php artisan make:migration create_tasks_table --create=tasks

Add string **name** and **user_id** on **date_create_tasks_table.php** from **database/migrations** directory.

```
public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned()->index();
        $table->string('name');
        $table->timestamps();
    });
}
```

**Note**: Make sure **lamp** or **xampp** is running. You will know if it's running when you can access **localhost/phpmyadmin/**.

**migrate** command will create all of databases tables.
> php artisan migrate

Check your table/s from your database(default db 'laravel' were used in this tutorial) in **localhost/phpmyadmin/**.

### Models (Eloquent models)
**make:model** command to generate **Task** model.
> php artisan make:model Task
Check **app/** directory for the newly created model **Task**.

Add the code on **app/Task.php**
```
/**
 * The attributes that are mass assignable.
 *
 * @var array
 */
protected $fillable = ['name'];
```