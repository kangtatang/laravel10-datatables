# DESCRIPTION (DESKRIPSI)

A sample app using Yajra datatables with Laravel 10.x

feature: Add, view and edit using modal and jquery ajax.

(Contoh aplikasi untuk menggunakan yajra datatables pada Laravel versi 10.x 
Belum menggunakan otentifikasi)


# INSTALATION (INSTALASI)

Local:

```BASH

composer update
npm install
npm run dev

# on different terminal run
php artisan migrate
php artisan serve

#access your app at : http://localhost:8000
```

# BUILD FROM SCRATCH

## Install Laravel

```BASH

laravel new app_name

```

or

```BASH

composer create-project laravel/laravel app_name

```

## Install library

```BASH
#install lravel/ui
composer require laravel/ui
php artisan ui bootsrap --auth
```

## Install yajra datatables

```bash
composer require yajra/laravel-datatables-oracle
```

## Setup provider and alias:

```PHP
'providers' => [
    // ...
    Yajra\DataTables\DataTablesServiceProvider::class,
],

'aliases' => [
    // ...
    'DataTables' => Yajra\DataTables\Facades\DataTables::class,
],

```

## setup in controller:

```php
use DataTables;

public function getData()
{
    $departments = Department::select(['id', 'name', 'description']);

    return DataTables::of($departments)
        ->addColumn('action', function ($department) {
            // Tambahkan kolom aksi sesuai kebutuhan Anda
        })
        ->toJson();
}

```

## setup in view:

```php
var table = $('#departmentTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('departments.getData') }}", //change to your controller route
    columns: [
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        { data: 'description', name: 'description' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
});

```

## setup style and script

```HTML
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
```

```html
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
    crossorigin="anonymous"
></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
```

## Import Libs

```php
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
```

## Set up env

I'm using sqlite for example
```BASH
...
DB_CONNECTION=sqlite
...
```

[Don't forget to Run migration]

*Notes: no seeder available
