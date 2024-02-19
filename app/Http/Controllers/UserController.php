<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Serah_terima;
use App\Models\User;
use Illuminate\Http\Request;

use App\Imports\KonsumenImport;
use App\Imports\UserImport;
use App\Models\Aksesrole;
use App\Models\Cabang;
use App\Models\Jabatan;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['perusahaan'] = Perusahaan::all();
        $data['role'] = Role::orderBy('id', 'asc')->get();
        $data['sidebar'] = 'user';

        $access = User::find(Auth::id())->getPermissionsViaRoles()->pluck('name')->toArray();

        $data['cabang'] = Cabang::whereIn('nama',User::getCabangByAccess('create','user',$access))->get();
        $data['cabang_edit'] = Cabang::whereIn('nama',User::getCabangByAccess('update','user',$access))->get();
        $data['user'] = User::whereIn('cabang', User::getCabangByAccess('read','user',$access))->orderBy('id', 'desc')->paginate(10);

        if (isset($_GET['search'])) {
            $data['user'] = User::search(User::getCabangByAccess('read','user',$access), $_GET['search']);
        }
        return view('pages.user', $data);
    }
    public function cari($id)
    {
        $data['user'] = User::where('id',$id)->first();
        return $data;
    }
    public function insert(Request $request)
    {
        $user = User::where('telepon_1', $_POST['telepon_1'])->count();
        if ($user == 0) {
            $user = new User;
            $user->nama = $_POST['nama'];
            $user->email = $_POST['email'];
            $user->password = Hash::make($_POST['password']);
            $user->perusahaan = $_POST['perusahaan'];
            $user->cabang = $_POST['cabang'];
            $user->jabatan = $_POST['jabatan'];
            $user->telepon_1 = $_POST['telepon_1'];
            $user->telepon_2 = $_POST['telepon_2'];
            $user->telepon_3 = $_POST['telepon_3'];
            $user->nik = $_POST['nik'];
            $user->npwp = $_POST['npwp'];
            $user->alamat = $_POST['alamat'];
            $user->desa_kelurahan = $_POST['desa_kelurahan'];
            $user->kecamatan = $_POST['kecamatan'];
            $user->kota_kabupaten = $_POST['kota_kabupaten'];
            $user->provinsi = $_POST['provinsi'];
            $user->status = $_POST['status'];
            $user->save();

            $user->syncRoles([$_POST['jabatan']]);

            return redirect('user')->with('berhasil', 'User berhasil ditambah');
        }else{
            return redirect('user')->with('gagal', 'Telepon sudah dipakai');
        }
    }
    public function edit($id, Request $request)
    {
        $user =  User::find($id);
        $user_count = User::where('telepon_1', $_POST['telepon_1'])->count();
        if($user->telepon_1 == $_POST['telepon_1']){
            $user_count = 0;
        }
        if ($user_count == 0) {
            $user->nama = $_POST['nama'];
            $user->email = $_POST['email'] ? $_POST['email'] : null;
            $user->password = $_POST['password'] ? Hash::make($_POST['password']) : null;
            $user->perusahaan = $_POST['perusahaan'];
            $user->cabang = $_POST['cabang'];
            $user->jabatan = $_POST['jabatan'];
            $user->telepon_1 = $_POST['telepon_1'];
            $user->telepon_2 = $_POST['telepon_2'] ? $_POST['telepon_2'] : null;
            $user->telepon_3 = $_POST['telepon_3'] ? $_POST['telepon_3'] : null;
            $user->nik = $_POST['nik'] ? $_POST['nik'] : null;
            $user->npwp = $_POST['npwp'] ? $_POST['npwp'] : null;
            $user->alamat = $_POST['alamat'] ? $_POST['alamat'] : null;
            $user->desa_kelurahan = $_POST['desa_kelurahan'] ? $_POST['desa_kelurahan'] : null;
            $user->kecamatan = $_POST['kecamatan'] ? $_POST['kecamatan'] : null;
            $user->kota_kabupaten = $_POST['kota_kabupaten'] ? $_POST['kota_kabupaten'] : null;
            $user->provinsi = $_POST['provinsi'] ? $_POST['provinsi'] : null;
            $user->status = $_POST['status'] ? $_POST['status'] : null;
            $user->save();

            $user->syncRoles([$_POST['jabatan']]);

            return redirect('user')->with('berhasil', 'User berhasil diedit');
        }else{
            return redirect('user')->with('gagal', 'Telepon sudah dipakai');
        }
    }
    public function status($id)
    {
        $user =  User::find($id);
        if ($user->status == 'Aktif') {
            $user->status = 'Tidak Aktif';
        } else {
            $user->status = 'Aktif';
        }
        $user->save();
        return redirect('user');
    }
    
    public function import()
    {
        Excel::import(new UserImport, request()->file('file'));
        return redirect('user');
    }

    public function unit($id)
    {
        $data = Serah_terima::join('user', 'serah_terima.id_pemilik_terakhir', '=', 'user.id')->where('serah_terima.id_unit', $id)->get();
        return json_encode($data);
    }
    
    public function browse($keyword)
    {
        
        $data['konsumen'] = !isset($_GET['search']) ? User::where('jabatan', 'Konsumen')->paginate(10) : User::browse($_GET['search']);
            
        return view('pages.browse_konsumen', $data);
    }
    
}
