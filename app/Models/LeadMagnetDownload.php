<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadMagnetDownload extends Model
{
    protected $fillable = ['lead_magnet', 'name', 'email'];
}
