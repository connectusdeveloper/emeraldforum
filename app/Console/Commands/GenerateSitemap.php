<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    protected $description = 'Command to generate sitemap';
    protected $signature = 'app:generate-sitemap';

    public function handle()
    {
        SitemapGenerator::create(url('/'))->writeToFile(public_path('sitemap.xml'));
        $this->line('Sitemap generate at ' . url('/sitemap.xml'));
    }
}
