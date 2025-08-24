<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
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
        $data['pelanggan'] = Kontak::with('additional_alamat')
                                    ->where('tipe','pelanggan')
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
            $data['additional_alamat'] = Alamat::where('id_kontak', $id)
                                                ->get();
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
        $additional_alamat = Alamat::where('id_kontak',$id);
        if(count($additional_alamat->get()) > 0){
            $additional_alamat->delete();
        }

        for($i = 0; $i < count($_POST['additional_alamat']); $i++){
            if($_POST['additional_alamat'][$i]){
                $additional_alamat = new Alamat;
                $additional_alamat->id_kontak = $id;
                $additional_alamat->alamat = $_POST['additional_alamat'][$i];
                $additional_alamat->save();
            }   
        }

        $pelanggan =  Kontak::find($id);
        $pelanggan->id_company = Auth::user()->id_company;
        $pelanggan->nama = $_POST['nama'];
        $pelanggan->nama_perusahaan = $_POST['nama_perusahaan'];
        $pelanggan->email = $_POST['email'];
        $pelanggan->nomor_handphone = $_POST['nomor_handphone'];
        $pelanggan->nomor_telepon = $_POST['nomor_telepon'];
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

    public function alamat()
    {
        $data['alamat'] = Alamat::where('id_kontak', $_GET['id'])
                                            ->get();
        return $data['alamat'];
    }
}
