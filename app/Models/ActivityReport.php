<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class ActivityReport extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships;

    protected $fillable = [
        'activityprogram_id',
        'institute_id',
        'user_id',
        'parent_id',
        'title',
        'type',
        'status',
        'month',
        'year',
        'pagu_indikatif',
        'sumber_dana',
        'progress_pekerjaan',
        'target_kinerja',
        'fisik',
        'non_fisik',
        'realization',
        'documentation',
        'ppk',
        'pptk',
        'percentage',
        'executor',
        'contract_price',
        'location',
        'target_fisik',
        'target_keuangan',
        'contract_number',
        'contract_date',
        'contract_duration',
        'target_progres',
        'realisasi_progres',
    ];

    public function program()
    {
        return $this->hasOne('App\Models\ActivityProgram', 'id', 'activityprogram_id')->withDefault();
    }

    public function institute()
    {
        return $this->hasOne('App\Models\Institute', 'id', 'institute_id')->withDefault();
    }

    public static function child($parent_id)
    {
        $rows = ActivityReport::where('parent_id', $parent_id)->count();
        return $rows;
    }

    public static function details($parent_id)
    {
        $rows_parent = ActivityReport::where('parent_id', $parent_id)->get()->pluck('id');
        $rows_details = ActivityReport::whereIn('parent_id', $rows_parent)->count();

        return $rows_details;
    }
}
