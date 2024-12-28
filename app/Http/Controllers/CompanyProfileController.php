<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Cors;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function company_profile($id)
    {
        $data['data'] = CompanyProfile::find($id)->first();
        return Cors::handle($data['data']);
    }
}
