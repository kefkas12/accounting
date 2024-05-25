<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function create(){
        $user = User::find(Auth::user()->id);
        $user->name = $_POST['name'];
        $user->save();

        return redirect('profil');
    }

    public function create_password(Request $request){
        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['Password lama tidak sama']
            ]);
        }else{
            if($request->password_baru != $request->konfirmasi_password){
                return back()->withErrors([
                    'konfirmasi_password' => ['Password anda tidak sesuai']
                ]);
            }
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($_POST['konfirmasi_password']);
            $user->save();
        }
        return redirect('profil/password');
    }

    public function create_company(){
        $data['sidebar'] = 'profil';
        return redirect('profil/company');
    }
}
