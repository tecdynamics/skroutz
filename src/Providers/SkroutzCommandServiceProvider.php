<?php
namespace Tec\Skroutz\Providers;

use Illuminate\Support\ServiceProvider;
use Tec\Skroutz\Commands\GenerateSkrutzXMLCommand;

class SkroutzCommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            GenerateSkrutzXMLCommand::class,
        ]);
    }
}
