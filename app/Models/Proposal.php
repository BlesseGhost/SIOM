<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_kegiatan',
        'file_proposal',
        'dibuat_oleh_user_id',
        'status',
        'current_reviewer_id',
        'file_revisi',
        'catatan_revisi',
        'ttd_pembina',
        'ttd_wr3',
        'total_dana',  // baru
        'sisa_dana'    // baru
    ];
}
