<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class Job_request_status extends Model
{
    use HasFactory;
    protected $table = 'job_request_status';
    
    public $timestamps = false;

}
