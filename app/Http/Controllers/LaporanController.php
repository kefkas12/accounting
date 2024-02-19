<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['sidebar'] = 'laporan';
        return view('pages.laporan.index', $data);
    }

    public function jurnal(){
        $data['sidebar'] = 'laporan';
        $data['jurnal'] = Jurnal::with('detail_jurnal.akun')
                                ->select('jurnal.*')
                                ->orderBy('jurnal.id','DESC')
                                ->get();
                                
        return view('pages.jurnal.index',$data);
    }
}
