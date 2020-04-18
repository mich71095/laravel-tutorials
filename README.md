# laravel-tutorials
Official Laravel 5.2 tutorials with task list app.


## Installation ##
Using Laravel 7.
https://laravel.com/docs/7.x


## Setup ##
> composer create-project laravel/laravel task-list-app --prefer-dist
and then
> cd task-list-app


## Database ##
##### Create table/s using **migration** and **migrate**. #####
**make:migration** command to generate a new database migration for **tasks** table.
> php artisan make:migration create_tasks_table --create=tasks

Add string **name** on date_create_tasks_table.php from **database/migrations** directory.
```
public function up()
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->increments('id'); // id should be auto increment
        $table->string('name'); //new code
        $table->timestamps();
    });
}
```

**Note**: Make sure **lamp** or **xampp** is running. You will know if it's running when you can access **localhost/phpmyadmin/**.

**migrate** command will create all of databases tables.
> php artisan migrate

Check your table/s from your database(default db 'laravel' were used in this tutorial) in **localhost/phpmyadmin/**.

##### **Models** (Eloquent models) #####
**make:model** command to generate **Task** model.
> php artisan make:model Task

Check **app/** directory for the newly created model **Task**.


## Routing ##

##### Adding routes  #####
Add routes on **routes/web.php**.

```
use App\Task;
use Illuminate\Http\Request;

/**
 * Show Task Dashboard
 */
Route::get('/', function () {
    //
});

/**
 * Add New Task
 */
Route::post('/task', function (Request $request) {
    //
});

/**
 * Delete Task
 */
Route::delete('/task/{task}', function (Task $task) {
    //
});
```

##### Display a view(html template) #####
Create a **tasks** view on **resources/views/** directory, **resources/views/tasks.blade.php**.

Connect **tasks** view to your router.
```
Route::get('/', function () {
    return view('tasks');
});
```

## Building Layouts & Views ##

##### Layout #####
Create a layout view **app.blade.php** on **resources/views/layouts** directory, **resources/views/layouts/app.blade.php**.
```
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Laravel Quickstart - Basic</title>

        <!-- CSS And JavaScript -->
    </head>

    <body>
        <div class="container">
            <nav class="navbar navbar-default">
                <!-- Navbar Contents -->
            </nav>
        </div>

        @yield('content')
    </body>
</html>
```

**Note**: `@yield('content')`, a special Blade directive which specify all child pages.

##### Child View #####
Add this code to **resources/views/tasks.blade.php**
```
@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- New Task Form -->
        <form action="{{ url('task') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Task</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="task-name" class="form-control">
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> Add Task
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TODO: Current Tasks -->
@endsection
```

`@extends` directive defines that we are using the layout, **resourecs/views/layouts/app.blade.php**.
Between `@section('content')` and `@endsection` are the content which were defined by `@yield('content')`at **app.blade.php** layout.
`@include('common.errors')` directive will load the template at **resources/views/common/errors.blade.php**.