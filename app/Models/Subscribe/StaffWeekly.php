<?php namespace App\Models\Subscribe;

use Illuminate\Database\Eloquent\Model;

class StaffWeekly extends Model
{

    protected $guarded = ['id'];

    protected $table = 'staff_weekly';

    protected $casts = [
        'mon' => 'array',
        'tue' => 'array',
        'wed' => 'array',
        'thu' => 'array',
        'fri' => 'array',
        'sat' => 'array',
        'sun' => 'array',
    ];
}
