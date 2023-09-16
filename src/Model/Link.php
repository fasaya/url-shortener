<?php

namespace Fasaya\UrlShortener\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'short_links';

    protected $fillable = [
        'slug',
        'short_url',
        'long_url',
        'clicks',
        'is_disabled',
        'expired_at',
        'created_by',
        'creator_ip',
        'deleted_by',
        'deleter_ip'
    ];

    public function linkClicks()
    {
        return $this->hasMany(LinkClick::class, 'short_link_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_at = date("Y-m-d H:i:s");
            $model->created_by = auth()->check() ? auth()->user()->id() : NULL;
            $model->creator_ip = request()->ip();
        });

        // self::updating(function ($model) {
        //     $model->updated_at = date("Y-m-d H:i:s");
        // });
    }
}
