<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah Data</title>
</head>
<body>
    <h1>Form Ubah Data User</h1>
    <a href="/user">Kembali</a>
    <br><br>

    <form action="/user/ubah_simpan/{{$data->user_id}}" method="POST">
        {{ csrf_field() }}
        {{method_field('PUT')}}

        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Masukkan Username" value="{{$data->username}}">
        <br>
        <label for="nama">Nama</label>
        <input type="text" name="nama" placeholder="Masukkan Nama" value="{{$data->nama}}">
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Masukkan Password" value="{{$data->password}}">
        <br>
        <label for="level_id">Level ID</label>
        <input type="number" name="level_id" placeholder="Masukkan ID Level" value="{{$data->level_id}}">
        <br>
        <input type="submit" class="btn btn-success" value="Ubah">
    </form>
</body>
</html>