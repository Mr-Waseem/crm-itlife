<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
File::deleteDirectory('resources/vendor/yajra');
	// $conn = new PDO("mysql:host=".env('DB_HOST').";dbname=".env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
	// $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return "Dd";
?>

<?php
$conn = DB::connection()->getPdo();
$quee = $conn->query('SELECT * from products')->delete();
return "D";
?>