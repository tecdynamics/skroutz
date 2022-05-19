<?php

namespace Tec\Skroutz\Commands;

use Exception;
use Illuminate\Console\Command;
use Tec\Setting\Supports\SettingStore;
use Tec\Skroutz\Models\SkroutzModel;

class GenerateSkrutzXMLCommand extends Command {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'skroutz:xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating product XML for the Skroutz';


    public function __construct() {
        parent::__construct();

    }

    /**
     * Execute the console command.
     */
    public function handle(SettingStore $settingStore) {
        try {
            if (setting('skroutz_enable', 0) < 1) {
                $this->info('Skroutz is not enabled');
                return;
            }
            $skroutz_last_update_time = setting('skroutz_last_update_time', 0);
            $skroutz_update_time = setting('skroutz_update_time', 'twicedaily');
            if ((int)$skroutz_last_update_time >= 0) {
                $SkroutzModel = new SkroutzModel();

                switch ($skroutz_update_time) {
                    case 'disabled':
                        break;
                    case 'hourly':
                        if (time() >= $skroutz_last_update_time) {
                            $SkroutzModel->build();
                            $settingStore->set('skroutz_last_update_time', strtotime('+1 hour'))->save();
                        }
                        break;

                    case 'twicedaily':
                        if (time() >= $skroutz_last_update_time) {
                            $SkroutzModel->build();
                            $settingStore->set('skroutz_last_update_time', strtotime('+12 hour'))->save();
                        }
                        break;
                    case 'daily':
                        if (time() >= $skroutz_last_update_time) {
                            $SkroutzModel->build();
                            $settingStore->set('skroutz_last_update_time', strtotime('+24 hour'))->save();
                        }
                        break;
                }
            }
        }
        catch (Exception $exception) {
            info($exception->getMessage());
        }
        $this->info('Xml has been generated successfully at ' . date('d-m-Y H:i:s'));
    }
}
