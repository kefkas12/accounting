<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'pelanggan';
        $data['pelanggan'] = Kontak::where('tipe','pelanggan')
                                    ->where('id_company',Auth::user()->id_company)
                                    ->get();
        return view('pages.pelanggan.index', $data);
    }

    public function pelanggan()
    {
        $data['sidebar'] = 'pelanggan';
        return view('pages.pelanggan.insert', $data);
    }

    public function insert(Request $request)
    {
        $pelanggan = new Kontak();
        $pelanggan->id_company = Auth::user()->id_company;
        $pelanggan->nama = $_POST['nama'];
        $pelanggan->nama_perusahaan = $_POST['nama_perusahaan'];
        $pelanggan->email = $_POST['email'];
        $pelanggan->nomor_handphone = $_POST['nomor_handphone'];
        $pelanggan->nomor_telepon = $_POST['nomor_telepon'];
        $pelanggan->alamat = $_POST['alamat'];
        $pelanggan->fax = $_POST['fax'];
        $pelanggan->npwp = $_POST['npwp'];
        $pelanggan->tipe = 'pelanggan';
        $pelanggan->save();

        return redirect('pelanggan');
    }
    public function detail($status=null,$id=null)
    {
        $data['sidebar'] = 'pelanggan';
        if($id){
            $data['pelanggan'] = Kontak::where('id', $id)
                                        ->where('id_company',Auth::user()->id_company)
                                        ->first();
            if($status == 'edit'){
                return view('pages.pelanggan.form', $data);
            }else if($status == 'detail'){
                return view('pages.pelanggan.detail', $data);
            }
        }else{
            return view('pages.pelanggan.form', $data);
        }  
    }
    public function edit($id, Request $request)
    {
        $pelanggan =  Kontak::find($id);
        $pelanggan->id_company = Auth::user()->id_company;
        $pelanggan->nama = $_POST['nama'];
        $pelanggan->nama_perusahaan = $_POST['nama_perusahaan'];
        $pelanggan->email = $_POST['email'];
        $pelanggan->nomor_handphone = $_POST['nomor_handphone'];
        $pelanggan->nomor_telepon = $_POST['nomor_telepon'];
        $pelanggan->alamat = $_POST['alamat'];
        $pelanggan->fax = $_POST['fax'];
        $pelanggan->npwp = $_POST['npwp'];
        $pelanggan->tipe = 'pelanggan';
        $pelanggan->save();

        return redirect('pelanggan');
    }

    public function hapus($id){
        $pelanggan = Kontak::find($id);
        $pelanggan->delete();

        return redirect('pelanggan');
    }
}
