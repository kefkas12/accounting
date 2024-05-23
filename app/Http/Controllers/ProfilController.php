<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(){
        $data['sidebar'] = 'profil';
        return view('pages.profil.index', $data);
    }

    public function password(){
        $data['sidebar'] = 'profil';
        return view('pages.profil.password', $data);
    }

    public function company(){
        $data['sidebar'] = 'profil';
        return view('pages.profil.company', $data);
    }

    public function insert(){
        $data['sidebar'] = 'profil';
        return redirect('profil', $data);
    }

    public function insert_password(){
        $data['sidebar'] = 'profil';
        return redirect('profil/password', $data);
    }

    public function insert_company(){
        $data['sidebar'] = 'profil';
        return redirect('profil/company', $data);
    }
}
