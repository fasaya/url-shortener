<?php

namespace Fasaya\UrlShortener\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkClick extends Model
{
    use HasFactory;

    protected $table = 'short_link_clicks';

    protected $fillable = [
        'short_link_id',
        'ip',
        'user_agent',
        'referer',
        'referer_host'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_at = date("Y-m-d H:i:s");
        });

        // self::updating(function ($model) {
        //     $model->updated_at = date("Y-m-d H:i:s");
        // });
    }
}
