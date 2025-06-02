<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class UserController extends Controller
{    
    // menampilkan halaman awal user
    public function index()
    {

        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        $activeMenu = 'user';

        $level = LevelModel::all(); // ambil data level untuk digunakan filter level

        return view('user.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'level' => $level]);

    }

    // Ambil data user dalam bentuk json untuk datatables  
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        //Filter data berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi   
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
 
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    // Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah user baru'
        ];
        $level = LevelModel::all();
        $activeMenu = 'user';
        return view('user.create', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'level' => $level]);
    }

    // Menampilkan halaman form tambah_ajax user
    public function create_ajax(){
        $level = LevelModel::select('level_id', 'level_nama')->get();
        
        return view('user.create_ajax')
                ->with('level', $level);    
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required | integer',
            'username' => 'required | string | min:3 | unique:m_user,username',
            'nama' => 'required | string | max:100',
            'password' => 'required | min:5'
        ]);

        UserModel::create([
            'level_id' => $request->level_id,
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password)
        ]);

        return redirect('/user')->with('success', 'Data user berhasil ditambahkan');
    }

    // public function store_ajax(Request $request)
    // {
    //     // cek apakah request berupa ajax
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'level_id' => 'required|integer',
    //             'username' => 'required|string|min:3|unique:m_user,username',
    //             'nama' => 'required|string|max:100',
    //             'password' => 'required|min:6',
    //         ];

    //         // use Illuminate\Support\Facades\Validator;
    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false, // response status, false: error/gagal, true: berhasil
    //                 'message' => 'Validasi Gagal',
    //                 'msgField' => $validator->errors(), // pesan error validasi
    //             ]);
    //         }

    //         UserModel::create($request->all());

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data user berhasil disimpan',
    //         ]);
    //     }

    //     return redirect('/');
    // }

    public function store_ajax(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Hash password sebelum insert
            $data = $request->all();
            $data['password'] = bcrypt($request->password);

            UserModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
            ]);
        }

        return redirect('/');
    }


    // menampilkan detail data user
    public function show($id)
    {
        $user = UserModel::with('level')->find($id);
 
         $breadcrumb = (object) [
             'title' => 'Detail User',
             'list' => ['Home', 'User', 'Detail'],
         ];
 
         $page = (object) [
             'title' => 'Detail user',
         ];
 
         $activeMenu = 'user';
 
         return view('user.show', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'user' => $user]);
    }


    public function show_ajax(string $id)
    {
        $user = UserModel::with('level')->find($id);
    
        if (!$user) {
            return view('user.show_ajax')->with('user', null);
        }
    
        return view('user.show_ajax', ['user' => $user]);
    }
    
    // menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];
        $page = (object) [
            'title' => 'Edit user'
        ];
        $activeMenu = 'user'; // set menu yang sedang aktif
        return view('user.edit', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'page' => $page, 'user' => $user, 'level' => $level]);
    }

    // Menampilkan halaman form edit user ajax
    public function edit_ajax(string $id)
     {
         $user = UserModel::find($id);
         $level = LevelModel::select('level_id', 'level_nama')->get();
 
         return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
     }

    // menyimpan perubahan data user
    public function update(Request $request, string $id)
     {
         $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter
            // dan bernilai unik di table m_user kolom username kecuali untuk user dengan id yang sedang diedit
             'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
             'nama' => 'required|string|max:100', // nama harus diisi, berupa string dan maksimal 100 karakter
             'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter)   dan bisa tidak diisi
             'level_id' => 'required|integer', // level_id harus diisi dan berupa angka
         ]);
 
         UserModel::find($id)->update([
             'username' => $request->username,
             'nama' => $request->nama,
             'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
             'level_id' => $request->level_id,
         ]);
 
         return redirect('/user')->with('success', 'Data user berhasil diubah');
     }

     public function update_ajax(Request $request, string $id)
     {
        // Cek apakah request berasal dari AJAX
         if ($request->ajax() || $request->wantsJson()) {

            // Validasi input
             $rules = [
                 'level_id' => 'required|integer',
                 'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                 'nama' => 'required|max:100',
                 'password' => 'nullable|min:6|max:20',
             ];
             // Jalankan Validasi
             // use Illuminate\Support\Facades\Validator;
             $validator = Validator::make($request->all(), $rules);

              // Jika validasi gagal, kirim respon JSON dengan pesan error
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false, // respon json, true: berhasil, false: gagal
                     'message' => 'Validasi gagal.',
                     'msgField' => $validator->errors(), // menunjukkan field mana yang error
                 ]);
             }
             
             $check = UserModel::find($id);
             if ($check) {
                 if (!$request->filled('password')) {
                     // jika password tidak diisi, maka hapus dari request
 
                     $request->request->remove('password');
                 }
                 $check->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate',
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan',
                 ]);
             }
         }
         return redirect('/');
     }

    // Menghapus ajax
    public function confirm_ajax(string $id)
     {
         $user = UserModel::find($id);
 
         return view('user.confirm_ajax', ['user' => $user]);
     }
 
     public function delete_ajax(Request $request, string $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $user = UserModel::find($id);
 
             if ($user) {
                 $user->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil dihapus'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/');
     }

    // Menghapus data user
    public function destroy(int $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }
        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Data user tidak bisa dihapus karena masih terdapat data terkait');
        }
    }

    public function import()
    {
        return view('user.import');
    }

    public function import_ajax(Request $request)
    {
        $rules = [
            'file_user' => ['required', 'mimes:xlsx,xls', 'max: 1024'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $file = $request->file('file_user');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $insert = [];
        $header = array_shift($rows); // Ambil header

        foreach ($rows as $row) {
            if (!empty($row[0])) {
                $insert[] = [
                    'username' => $row[0],
                    'nama' => $row[1],
                    'password' => bcrypt($row[2]),
                    'level_id' => $row[3],
                ];
            }
        }

        if (!empty($insert)) {
            UserModel::insertOrIgnore($insert);

            return redirect('/user')->with('success', 'Data user berhasil diimport: ' . count($insert) . ' record');
        } else {
            return redirect('/user')->with('error', 'Data user kosong');
        }
    }

    public function export_excel()
    {
        $user = UserModel::select('username', 'nama', 'password', 'level_id')->orderBy('level_id')->with('level')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Password');
        $sheet->setCellValue('E1', 'Level');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->username);
            $sheet->setCellValue('C' . $baris, $value->nama);
            $sheet->setCellValue('D' . $baris, $value->password);
            $sheet->setCellValue('E' . $baris, $value->level->level_nama);
            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data User');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data User ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: no-cache');
        $writer->save('php://output');
        exit;
    }
}