<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\KaryawanResource;
use App\Http\Resources\CutiResource;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\CutiController;

  
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
  
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

//Menerapkan Laravel Sanctum Package
Route::middleware('auth:sanctum')->group( function () {
    Route::namespace('App\Http\Controllers')->group(function () {
        /*
            1. CRUD Karyawan
                                */
    
        Route::post('create_karyawan', 'KaryawanController@create'); //Create  Karyawan
        Route::get('index_karyawan', 'KaryawanController@index'); //Read Karyawan
        Route::put('update_karyawan/{id}', 'KaryawanController@update'); //Update Karyawan
        Route::delete('delete_karyawan/{id}', 'KaryawanController@destroy'); //Delete Karyawan
        /*
            2. Tampilkan 3 karyawan yang pertama kali bergabung.
                                                                    */
                            
        Route::get('index_oldest_karyawan', 'KaryawanController@index_oldest_karyawan');
        
        /*
            3. Tampilkan daftar karyawan yang saat ini pernah mengambil cuti.
                                                                                */
        Route::get('index_ever_cuti', 'CutiController@index_ever_cuti');
        
        /*
            4. Tampilkan sisa cuti setiap karyawan (quota cuti 12 hari/tahun). Daftar berisikan; nomor_induk, nama, sisa_cuti.
                                                                                                                                */
        Route::get('index_rest_cuti', 'CutiController@index_rest_cuti');

        /*
            Read Cuti
                        */
        Route::get('index_cuti', 'CutiController@index');
    });
    //Logout
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});

/*
INSERT INTO karyawans (nomor_induk, nama, alamat, tanggal_lahir, tanggal_bergabung, created_at, updated_at)
VALUES
	('IP06001', 'Agus', 'Jln Gaja Mada no 12, Surabaya', '1980-01-11', '2005-08-07', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
	('IP06002', 'Amin', 'Jln Imam Bonjol no 11, Mojokerto', '1977-09-03', '2005-08-07', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
	('IP06003', 'Yusuf', 'Jln A Yani Raya 15 No 14 Malang', '1973-08-09', '2006-08-07', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
	('IP06004', 'Alyssa', 'Jln Bungur Sari V no 166, Bandung', '1983-03-18', '2006-09-06', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
	('IP06005', 'Maulana', 'Jln Candi Agung, No 78 Gg 5, Jakarta', '1978-11-10', '2006-09-10', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06006', 'Agfika', 'Jln Nangka, Jakarta Timur', '1979-02-07', '2007-01-02', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06007', 'James', 'Jln Merpati, 8 Surabaya', '1989-05-18', '2007-04-04', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06008', 'Octavanus', 'Jln A Yani 17, B 08 Sidoarjo', '1985-04-14', '2007-05-19', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06009', 'Nugroho', 'Jln Duren tiga 167, Jakarta Selatan', '1984-01-01', '2008-01-16', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06010', 'Raisa', 'Jln Kelapa Sawit, Jakarta Selatan', '1990-12-17', '2008-08-16', '2023-07-26 01:13:41', '2023-07-26 01:13:41');

INSERT INTO cutis (nomor_induk, tanggal_cuti, lama_cuti, keterangan, created_at, updated_at)
VALUES
	('IP06001', '2020-08-02', '2', 'Acara Keluarga', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
	('IP06001', '2020-08-18', '2', 'Anak Sakit', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
	('IP06006', '2020-08-19', '1', 'Nenek Sakit', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06007', '2020-08-23', '1', 'Sakit', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06004', '2020-08-29', '5', 'Menikah', '2023-07-26 01:13:41', '2023-07-26 01:13:41'),
    ('IP06003', '2020-08-30', '2', 'Acara Keluarga', '2023-07-26 01:13:41', '2023-07-26 01:13:41'); 
*/