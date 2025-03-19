<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // public function index()
    // {
    //     // tambah data user dengan Eloquent Model
    //     // $data = [
    //     //     'nama' => 'Pelanggan Pertama',
    //     // ];
    //     // UserModel::where('username', 'customer-1')->update($data); //update data user

    //     // $data = [
    //     //     'username' => 'customer-1',
    //     //     'nama' => 'Pelanggan',
    //     //     'password' => Hash::make('12345'),
    //     //     'level_id' => 3
    //     // ];
    //     // UserModel::insert($data); // tambahkan data ke table m_user

    //     // $data = [
    //     //     'level_id' => '2',
    //     //     'username' => 'manager_tiga',
    //     //     'nama' => 'Manager 3',
    //     //     'password' => Hash::make('12345')
    //     // ];
    //     // UserModel::create($data);

    //     //coba akses model UserModel
    //     // $user = UserModel::create(
    //     //     [
    //     //         'username' => 'manager55',
    //     //         'nama' => 'Manager55',
    //     //         'password' => Hash::make('12345'),
    //     //         'level_id' => 2
    //     //     ]
    //     // );
    //     // $user->username= 'manager56';

    //     // $user->isDirty(); // true
    //     // $user->isDirty('username'); // true
    //     // $user->isDirty('nama'); // false
    //     // $user->isDirty('nama', 'username'); // true

    //     // $user->isClean(); // false
    //     // $user->isClean('username'); // false
    //     // $user->isClean('nama'); // true
    //     // $user->isClean('nama', 'username'); // false
        
    //     // $user->save();

    //     // $user->isDirty(); // false
    //     // $user->isClean(); // true
    //     // dd($user->isDirty());

    //     //coba akses model UserModel
    //     // $user = UserModel::create(
    //     //     [
    //     //         'username' => 'manager11',
    //     //         'nama' => 'Manager11',
    //     //         'password' => Hash::make('12345'),
    //     //         'level_id' => 2
    //     //     ]
    //     // );
    //     // $user->username= 'manager12';

    //     // $user->save();

    //     // $user->wasChanged(); // true
    //     // $user->wasChanged('username'); // true
    //     // $user->wasChanged('nama'); // false
    //     // $user->wasChanged('nama', 'username'); // true
    //     // dd($user->wasChanged(['nama', 'username'])); //true

    //     //coba akses model UserModel
    //     $user = UserModel::all();
    //     return view('user', ['data' => $user]);
    // }

    public function index()
    {
        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }

    public function tambah(){
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ]);        
        return redirect('/user');
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id, Request $request){
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->level_id = $request->level_id;

        $user->save();
        return redirect('/user');
    }

    public function hapus($id){
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }
}