<?php namespace App\Models\Station;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StationStaffs extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'station_staffs';
}
