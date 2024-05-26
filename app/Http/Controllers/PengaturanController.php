<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Approver;
use App\Models\Company;
use App\Models\Kontak;
use App\Models\Requester;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PengaturanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'pengaturan';
        $data['pengaturan'] = Kontak::where('tipe', 'supplier')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.pengaturan.sidebar', $data);
    }
    
    public function pengguna()
    {
        $data['sidebar'] = 'pengaturan';
        $data['pengguna'] = User::where('id_company',Auth::user()->id_company)
                                ->get();
        
       
        // User::find(9)->assignRole('pergudangan');
        // $role = Role::create(['name' => 'pergudangan']);

        // $permission = Permission::create(['name' => 'create jurnal']);
        // $permission = Permission::create(['name' => 'read jurnal']);
        // $permission = Permission::create(['name' => 'update jurnal']);
        // $permission = Permission::create(['name' => 'delete jurnal']);

        // Role::find(3)->givePermissionTo(4);

        return view('pages.pengaturan.pengguna', $data);
    }
    public function form_pengguna($id = null)
    {
        $data['sidebar'] = 'pengaturan';
        $data['role'] = Role::get();
        if($id){
            $data['is_edit'] = true;
            $data['user'] = User::where('id',$id)->first();
            $data['my_role'] = $data['user']->getRoleNames()->toArray();
        }
        
        return view('pages.pengaturan.form_pengguna', $data);
    }

    public function insert_form_pengguna()
    {
        $user = new User();
        $user->id_company = Auth::user()->id_company;
        $user->nama_perusahaan = Company::find($user->id_company)->nama_perusahaan;
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = Hash::make($_POST['password']);
        $user->role = 0;
        $user->save();

        for($i= 0;$i<count($_POST['role']); $i++){
            $user->assignRole($_POST['role'][$i]);
        }

        return redirect('pengaturan/pengguna');
    }

    public function edit_form_pengguna($id){
        $user = User::find($id);
        $user->syncRoles([]);
        for($i= 0;$i<count($_POST['role']); $i++){
            $user->assignRole($_POST['role'][$i]);
        }

        return redirect('pengaturan/pengguna');
    }

    public function hapus_form_pengguna($id)
    {
        $user = User::find($id);
        $user->syncRoles([]);
        $user->delete();

        return redirect('pengaturan/pengguna');
    }

    public function perusahaan()
    {
        $data['sidebar'] = 'pengaturan';
        $data['perusahaan'] = Company::where('id',Auth::user()->id_company)
                                    ->first();
        return view('pages.pengaturan.perusahaan', $data);
    }

    public function form_perusahaan()
    {
        $data['sidebar'] = 'pengaturan';
        $data['perusahaan'] = Company::where('id',Auth::user()->id_company)
                                    ->first();
        return view('pages.pengaturan.form_perusahaan', $data);
    }

    public function insert_form_perusahaan()
    {
        $company = Company::find(Auth::user()->id_company);
        if($_POST['nama_perusahaan']){
            $company->nama_perusahaan = $_POST['nama_perusahaan'];
        }
        if($_POST['alamat_perusahaan']){
            $company->alamat_perusahaan = $_POST['alamat_perusahaan'];
        }
        if($_POST['nomor_telepon']){
            $company->nomor_telepon = $_POST['nomor_telepon'];
        }

        $user = User::find(Auth::user()->id);
        if($_POST['nama_perusahaan']){
            $user->nama_perusahaan = $_POST['nama_perusahaan'];
        }
        if($_FILES['logo_perusahaan']){
            $filename = $_POST['nama_perusahaan'].'.'.request()->logo_perusahaan->getClientOriginalExtension();
            request()->logo_perusahaan->move(public_path('argon/img/brand'), $filename);

            $company->logo_perusahaan = $filename;
            $user->logo_perusahaan = $filename;
        }
        $company->save();
        $user->save();

        return redirect('pengaturan/perusahaan');
    }

    public function approval()
    {
        $data['sidebar'] = 'pengaturan';
        $data['approval'] = Approval::where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.pengaturan.approval', $data);
    }

    public function form_approval()
    {
        $data['sidebar'] = 'pengaturan';
        $data['requester'] = User::where('id_company',Auth::user()->id_company)->get();
        $data['approver'] = User::where('id_company',Auth::user()->id_company)->get();
        $data['approval'] = Company::where('id',Auth::user()->id_company)
                                    ->first();
        return view('pages.pengaturan.form_approval', $data);
    }

    public function insert_form_approval()
    {
        $approval = new Approval;
        $approval->nama = $_POST['nama'];
        $approval->tipe_transaksi = $_POST['tipe_transaksi'];
        $approval->id_company = Auth::user()->id_company;
        $approval->save();

        $requester = new Requester;
        $requester->id_approval = $approval->id;
        $requester->id_user = $_POST['requester'];
        $requester->save();
        
        $approver = new Approver;
        $approver->id_approval = $approval->id;
        $approver->id_user = $_POST['approver'];
        $approver->save();

        return redirect('pengaturan/approval');
    }
}
