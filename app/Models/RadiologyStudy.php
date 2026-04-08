<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadiologyStudy extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'modality',
        'body_part',
        'duration_minutes',
        'contrast_needed',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function radiologyStudies()
    {
        return $this->hasMany(RadiologyStudy::class);
    }
}
