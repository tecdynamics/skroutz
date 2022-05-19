<?php

namespace Tec\Skroutz\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Tec\Base\Traits\LoadAndPublishDataTrait;
use Tec\Skroutz\Commands\GenerateSkrutzXMLCommand;
use Tec\Skroutz\Http\Controllers\SkroutzController;
use Tec\Skroutz\Models\SkroutzModel;

class SkroutzServiceProvider extends ServiceProvider {
    use LoadAndPublishDataTrait;

    public function boot() {
        $this->setNamespace('plugins/skroutz')
            ->loadAndPublishConfigurations(['general'])
            ->loadHelpers()
            ->loadRoutes(['web'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(SkroutzCommandServiceProvider::class);


        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-skroutz',
                    'priority' => 5,
                    'parent_id' => null,
                    'name' => 'plugins/skroutz::skroutz.name',
                    'icon' => 'fas fa-store',
                    'url' => '',
                    'permissions' => ['skroutz.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-skroutz-settings',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-skroutz',
                    'name' => 'plugins/skroutz::skroutz.settings',
                    'icon' => 'fas fa-cogs',
                    'url' => route('skroutz.index'),
                    'permissions' => ['skroutz.settings'],
                ]);


        });
        $this->app->booted(function () {

            $scroutzModel = new SkroutzModel();
            add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$scroutzModel, 'saveSkroutzData'], 128, 3);

            add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$scroutzModel, 'updateSkroutzData'], 128, 3);

            $SkroutzController = new SkroutzController();
            add_action(BASE_ACTION_META_BOXES, [$SkroutzController, 'doMetaBoxes'], 8, 3);

      $this->app->make(Schedule::class)->command(GenerateSkrutzXMLCommand::class)->hourly();
        });


    }


}
