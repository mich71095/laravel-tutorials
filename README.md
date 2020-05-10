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

### Relationships (Eloquent relationships)
Add the code on **app/User.php**
```
/**
 * Get the user that owns the task.
 */
public function user()
{
    return $this->belongsTo(User::class);
}

```

Add the code on **app/Task.php**
```
/**
 * Get all of the tasks for the user.
 */
public function tasks()
{
    return $this->hasMany(Task::class);
}
```

## Routing
### Display a view (html template)
Create a **tasks** view on **resources/views/** directory, **resources/views/tasks.blade.php**.

Add the code to connect **tasks** view to your router, **routes/web.php**.
```
Route::get('/', function () {
    return view('tasks');
});
```

## Authentication
> composer require laravel/ui
> php artisan ui vue --auth

Check **routes/web.php**. New codes will be added.

```
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
```
Delete:
```
Route::get('/home','HomeController@index')->name('home');
Route::get('/', function () {
	return view('tasks');
});
```

Check **Controllers** and delete **HomeController.php**.
Check **resources** and delete **welcome.blade.php** and **home.balde.php**.

## The Task Controller
> php artisan make:controller TaskController

Check **routes/web.php** and new codes.
```
Route::get('/tasks', 'TaskController@index')->name('tasks');
Route::post('/task', 'TaskController@store');
Route::delete('/task/{task}', 'TaskController@destroy');
```
Check **TaskController.php** and new code to authenticate all routes and to connect your view.
```
public function __construct()
{
    $this->middleware('auth');
}

public function index()
{
    return view('tasks');
}
```

## Views
Check **routes/web.php** and edit code.
```
Route::get('/tasks', 'TaskController@index');
```
Check **TaskController.php** and edit code.
```
public function index(Request $request)
{
    return view('tasks.index');
}
```
Create this file **resources/views/tasks/index.blade.php** and this code.
```
@extends('layouts.app')

@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->

        <!-- New Task Form -->
        <form action="{{ url('task') }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            <!-- Task Name -->
            <div class="form-group">
                <label for="task-name" class="col-sm-3 control-label">Task</label>

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
Delete **tasks.blade.php** from **resources/views/**.

### Adding Tasks
## Validation
Check **TaskController.php** and add this code.
```
/**
 * Create a new task.
 *
 * @param  Request  $request
 * @return Response
 */
public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|max:255',
    ]);

    // Create The Task...
}
```
Create this file **resources/views/common/errors.blade.php** and add this code.
```
@if (count($errors) > 0)
    <!-- Form Error List -->
    <div class="alert alert-danger">
        <strong>Whoops! Something went wrong!</strong>

        <br><br>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```
Check **index.blade.php** and add this code.
```
<!-- Display Validation Errors -->
@include('common.errors')
```

##Creating The Task
Check **TaskController.php** and add this code.
```
/**
 * Create a new task.
 *
 * @param  Request  $request
 * @return Response
 */
public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|max:255',
    ]);

    $request->user()->tasks()->create([
        'name' => $request->name,
    ]);

    return redirect('/tasks');
}
```

### Displaying Existing Tasks
## Dependency Injection
Create an **app/Repositories** directory, add a **TaskRepository** class and add this code.
```
<?php

namespace App\Repositories;

use App\User;

class TaskRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    public function forUser(User $user)
    {
        return $user->tasks()
                    ->orderBy('created_at', 'asc')
                    ->get();
    }
}
```
## Injecting the Repository
Check your **TaskController.php**.
It should have these code.
```
use App\Task;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
```
Add this code.
```
/**
 * The task repository instance.
 *
 * @var TaskRepository
 */
protected $tasks;
```
Edit **__construct**.
```
public function __construct(TaskRepository $tasks)
{
    $this->middleware('auth');

    $this->tasks = $tasks;
}
```
Edit **index**.
```
return view('tasks.index', [
    'tasks' => $this->tasks->forUser($request->user()),
]);
```
## Displaying The Tasks
Check **index.blade.php** and add this code.
```
<!-- Current Tasks -->
@if (count($tasks) > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            Current Tasks
        </div>

        <div class="panel-body">
            <table class="table table-striped task-table">

                <!-- Table Headings -->
                <thead>
                    <th>Task</th>
                    <th>&nbsp;</th>
                </thead>

                <!-- Table Body -->
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <!-- Task Name -->
                            <td class="table-text">
                                <div>{{ $task->name }}</div>
                            </td>

                            <td>
                                <!-- TODO: Delete Button -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
```

### Deleting Tasks
## Adding The Delete Button
Check **index.blade.php** and add this code.
```
<!-- Delete Button -->
<td>
    <form action="{{ url('task/'.$task->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <button type="submit" id="delete-task-{{ $task->id }}" class="btn btn-danger">
            <i class="fa fa-btn fa-trash"></i>Delete
        </button>
    </form>
</td>
```
**Note** that the delete button's form method is listed as **POST**, even though we are responding to the request using a **Route::delete** route. **HTML forms** only allow the **GET** and **POST** HTTP verbs, so we need a way to spoof a **DELETE** request from the form.

We can spoof a **DELETE request** by outputting the results of the **method_field('DELETE')** function within our form. This function generates a hidden form input that Laravel recognizes and will use to override the actual HTTP request method.

## Route Model Binding
Check **TaskController** and add this code.
```
/**
 * Destroy the given task.
 *
 * @param  Request  $request
 * @param  Task  $task
 * @return Response
 */
public function destroy(Request $request, Task $task)
{
    //
}
```

## Authorization
We have no guarantee that the authenticated user actually "owns" the given task. For example, a malicious request could have been concocted in an attempt to delete another user's tasks by passing a random task ID to the /tasks/{task} URL. So, we need to use Laravel's authorization capabilities to make sure the authenticated user actually owns the Task instance that was injected into the route.

## Creating A Policy
> php artisan make:policy TaskPolicy
Check **app/Policies/TaskPolicy.php** and add this code.
```
use App\Task;

public function destroy(User $user, Task $task)
{
    return $user->id === $task->user_id;
}
```
Check **app/Providers/AuthServiceProvider.php** and add this code.
```
protected $policies = [
    'App\Task' => 'App\Policies\TaskPolicy',
];
```