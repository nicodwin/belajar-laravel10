<?php

namespace App\Http\Controllers;

use App\Imports\MultipleSheetsImport;
use App\Imports\UserImport;
use App\Models\User;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Return_;

class BelajarController extends Controller
{
    public function cache(Request $request)
    {

        $data = Cache::remember('users', now()->addMinutes(5), function () {
            return User::get();
        });

        return view('belajar.cache', compact('data'));
    }

    public function import(Request $request)
    {

        return view('import');
    }

    public function import_proses(Request $request)
    {
        try {

            Excel::import(new MultipleSheetsImport(), $request->file('file'));
            return redirect()->back();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function enkripsi(Request $request)
    {
        $string   = "Saya suka makan sate padang";
        $enkripsi = Crypt::encryptString($string);
        $deskripsi = Crypt::decryptString($enkripsi);

        echo "String : " . $string . "<br><br>";
        echo "Hasil Enkripsi : " . $enkripsi . "<br><br>";
        echo "Hasil Dekripsi : " . $deskripsi;

        $params = [
            'nama' => 'Nico Dwi Novianto',
            'hobby' => 'Mendengar Musik',
            'makanan_favorit' => 'Sate Padang',
        ];

        $params = Crypt::encrypt($params);

        echo "<a href=" . route('enkripsi-detail', ['params' => $params]) . ">Lihat detail disini</a>";
    }

    public function enkripsi_detail(Request $request, $params)
    {
        $params = Crypt::decrypt($params);

        dd($params);
    }

    public function hashing()
    {
        $string = 'SayaNico123!';
        $hash = Hash::make($string);

        echo "String 1 : " . $string . "<br><br>";
        echo "Hasil Hashing 1 : " . $hash;
        echo "<hr>";
        $string2 = 'SayaNico123!.';
        $hash2 = Hash::make($string2);

        echo "String 2 : " . $string2 . "<br><br>";
        echo "Hasil Hashing 2: " . $hash2;

        $pengecekan = Hash::check($string2, $hash);
        echo "<hr>";
        if ($pengecekan) {
            echo "It is match !";
        } else {
            echo "Not Match!";
        }
    }
}
