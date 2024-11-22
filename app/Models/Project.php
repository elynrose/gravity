<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Video;

class Project extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'projects';

    public const GENDER_SELECT = [
        'male' => 'Male',
        'female' => 'Female',
    ];

    public const PRIVACY_RADIO = [
        '0' => 'Private',
        '1' => 'Public',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'gender',
        'prompt',
        'script',
        'status',
        'inputMethod',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();
        self::observe(new \App\Observers\ProjectActionObserver);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function video($id){
        return Video::where('project_id', $id)->first();
    }

    public function avatar()
    {
        return $this->hasOne(Avatar::class, 'project_id');
    }

}
