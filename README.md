# Multiple-QR-codes-in-laravel-8
How to generate multiple QRCodes for multiple users at same time Using Laravel 8 and Mysql
**Step 1: Laravel 8 Installation

To get started if you do not already have a Laravel installation you are working with then you need to run a fresh installation as follows;
Open terminal and navigate to your htdocs folder if you are using xampp and then run the following command;

composer create-project --prefer-dist laravel/laravel blog

**Step 2: QRCode Package Installation (simple-qrcode package)

Now navigate into your new Laravel installation (blog) and install simple-qrcode package by running the following;

composer require simplesoftwareio/simple-qrcode

To add service provider and aliase, open config/app.php file and add the following;

config/app.php
'providers' => [
    ....
    SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class
],
'aliases' => [
    ....
    'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class
],


**Step 3: Create the products table using a migration file

php artisan make:migration create_products_table

**Step 4: create your database (blog_db) and connect it to your .env file

.env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_db
DB_USERNAME=root
DB_PASSWORD=

**Step 5: Add tables to your database 

php artisan migrate

**Step 6: Add dummy records to your users table using factory and seeder

Edit DatabaseSeeder by uncommenting the line under public function run() as follows;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(10)->create();
    }
}


**Step 7: Run the seed command to populate the users table with dummy records

php artisan db:seed

**Step 8: Create your UsersController 

php artisan make:controller UsersController

app/controllers/UsersController.php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function index(){
        $data = User::all();
        
        return view ('users.index', compact('data'));
    }

    public function show($id){
        $data = User::find($id);

        return view('users.show', compact('data'));
    }
}


**Step 9: Create your routes: go to your web.php file under routes folder and add the users route as follows;

Routes/web.php

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UsersController::class);

**Step 10: View users and generate QRCodes on your index page for each user using their unique id

Create the users folder under resources/views/users and add the following files:

index.blade.php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <title>Users and QRcodes</title>
</head>
<body>
    <h2>List of Users and their QRCodes</h2>
    <table class="table table-striped">
        <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">QRCode</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $d)
            <tr>
              <th scope="row">{{ $d->id }}</th>
              <td>{{ $d->name }}</td>
              <td>{{ $d->email }}</td>
              <td>{{ QrCode::size(80)->generate('http://localhost/users/'.$d->id) }}</td>
              <td>
                <a href="{{ route('users.show', $d->id) }}" class="btn btn-info btn-lg">View</a>      
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
</body>
</html>



users/show.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <title>Users and QRcodes</title>
</head>
<body>
    <h2>User Profile</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $data->email }}</td>
            </tr>
        </table>
</body>
</html>


**Please take note (remember to change the url to best suit your own add for the generated QRCode 
   <td>
                  {{ QrCode::size(80)->generate('http://192.168.169.168/blog/public/users/'.$d->id) }}</td>
              <td>

Now scan any QRCode and have access to individual profile page.**

Thanks
![image](https://user-images.githubusercontent.com/19743503/155631639-d58bf20c-635f-45a6-8003-3f17edffcb97.png)
