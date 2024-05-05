<?php

namespace App\Http\Controllers;

use App\Models\Jarak;
use App\Models\Suhu;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function iot_read($alat)
    {
        if($alat == 'suhu'){
            $data['suhu'] = Suhu::get();
        }else if($alat == 'jarak'){
            $data['jarak'] = Jarak::get();
        }
        return $data;
    }

    public function iot_create(Request $request, $alat)
    {
        if($alat == 'suhu'){
            $data['alat'] = Suhu::find(1);
            $data['alat']->kelembaban = $request->get('kelembaban');
            $data['alat']->suhu = $request->get('suhu');
            $data['alat']->save();
        }else if($alat == 'jarak'){
            $data['alat'] = Jarak::find(1);
            $data['alat']->jarak = $request->get('jarak');
            $data['alat']->save();
        }
        return $data['alat'];
    }
    
    public function jarak(){
        $data['jarak'] = Jarak::get();
        return view('jarak',$data);
    }
    
    public function suhu(){
        $data['suhu'] = Suhu::get();
        return view('suhu',$data);
    }
}
