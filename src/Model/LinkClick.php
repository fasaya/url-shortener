<?php

namespace Fasaya\UrlShortener\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkClick extends Model
{
    use HasFactory;

    protected $table = 'short_link_clicks';

    protected $fillable = [
        'ip',
        'user_agent',
        'referer',
        'referer_host',
        'link_id'
    ];
}
