<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NF525FiscalArchive extends Model
{
    protected $fillable = [
        'archive_date',
        'archive_file_path',
        'archive_hash',
        'is_verified',
        'verification_date'
    ];

    protected $casts = [
        'archive_date' => 'date',
        'is_verified' => 'boolean',
        'verification_date' => 'datetime'
    ];
} 