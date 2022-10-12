<?php

namespace App\Console\Commands;

use App\Entities\Campaign\Models\Campaign;
use App\Entities\MobileDevice\Models\MobileDevice;
use App\Entities\MobileDevice\Notifications\Android;
use App\Entities\MobileDevice\Notifications\Ios;
use Illuminate\Console\Command;

class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Campaign send';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 900000000);
        $this->info('Start');
        file_put_contents(
            storage_path('/log-push-' . date('Y-m-d') . '.txt'),
            "Start\n",
            FILE_APPEND
        );

        $campaign = Campaign::where([
            ['publish_at', '<=', now()],
            ['status', 'Запланирована']
        ])->first();

        if ($campaign) {
            $campaign->status = 'Отправка выполнена';
            $campaign->save();

            $mobileDevicies = MobileDevice::query()
                ->when($campaign->type === 'system', function ($query) {
                    return $query->where('enable_push_promotion', true)
                        ->orWhere('enable_push_event', true)
                        ->orWhere('enable_push_children', true);
                })
                ->when($campaign->type === 'event', function ($query) {
                    return $query->where('enable_push_event', true);
                })
                ->when($campaign->type === 'promotion', function ($query) {
                    return $query->where('enable_push_promotion', true);
                })
                ->when($campaign->type === 'children', function ($query) {
                    return $query->where('enable_push_children', true);
                })
                ->get();

            foreach ($mobileDevicies as $mobileDevice) {
                if ($mobileDevice->os === 'iOS') {
                    $mobileDevice->notify(new Ios($campaign));
                } else {
                    $mobileDevice->notify(new Android($campaign));
                }
            }
        }

        $this->info('End');
    }
}
