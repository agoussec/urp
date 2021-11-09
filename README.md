# URP 
---------------------------------------

## A laravel package for kickstarting user role and permission project


**URP** stands for user role permissions. A basic package for kickstarting laravel project that contain multiple user roles and permission.


## Features
---------------------------------------

- Publishable role and permission migrations 
- Create user `roles` and `permissions` using artisan command line
- Assign `roles` and `permissions` to user from artisan command
- Role & Permission middleware
- Check `$user->hasPermissionTo`
- Check `$user->hasRole`
- Blade `@can`, `@cannot` directive

## 
## Installation
---------------------------------------

URP requires [Laravel](https://laravel.com/) 5.3+ to run.

Install using `composer` on fresh laravel project.

```sh
composer require agoussec/urp
```


## Usage
---------------------------------------
##### Console commands -

Creating User role
```
php artisan role:add {rolename} {slug?}
```

Listing all added user roles
```
php artisan role:list
```


Assign role to user
```
php artisan assign:role {roleslug} {usermail}
```


Create permission
```
php artisan permission:add {permissionname} {slug?}
```


Listing all added permissions
```
php artisan permission:list
```

### Migration
Migration file will atoumatically load after installation of package. just need to run `migrate` cammand.

    php artisan migrate
    
### Middleware

`RoleMiddleware`
    
    // In Route
        Route::get('test/middleware', [TestController::class, 'testShow'])->name('test.role')->middleware('role:role1|role2,permission');
### Controller
    
`can`

    if($request->user()->can('create-tasks')) {
        //Code goes here
    }
    
`role`

    $user->hasRole('developer')
    
`permission`

    $user->givePermissionsTo('create-tasks')
    
### Blade directive

`Role`

    @role('developer')
        Hello developer
    @endrole
    
`can`
    
    @can('add-course')
        <li class="nav-item"><a href="{{ route('admin.course.add') }}" class="nav-link">Add New Course</a></li>
    @endcan
    
    @canany(['view-course', 'edit-course', 'delete-course'])
        <li class="nav-item "><a href="{{ route('admin.course.manage') }}" class="nav-link">Manage Courses</a></li>
    @endcanany

## TODOs
---------------------------------------------------------------
* Console commands for removing `roles`, `permissions`
* Commands for giving `permission` to specific user or user `role`
* Policies logic around a particular model or resource.

## Credits
---------------------------------------------------------------

* [laravelcode](https://www.laravelcode.com/post/laravel-7-user-roles-and-permissions-tutorial-without-packages)


## License
---------------------------------------------------------------

MIT

**Free Software, Hell Yeah!**

