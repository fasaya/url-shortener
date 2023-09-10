<?php

namespace Fasaya\UrlShortener\Commands;

use Illuminate\Console\Command;

class UrlShortenerCommand extends Command
{
    public $signature = 'url-shortener';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
