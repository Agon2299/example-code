<?php

namespace App\Console\Commands;

use App\Entities\Cashbox\Models\Cashbox;
use App\Entities\Category\Models\Category;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\Models\Subcategory;
use App\Entities\Tag\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RemoveLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove log file';

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
        $files = glob(storage_path('/*.txt'));
        $time = time() - (7 * 24 * 60 * 60);
        foreach ($files as $file) {
            if (filectime($file) < $time) {
                unlink($file);
            }
        }

        $files = glob(storage_path('/logs/*.log'));
        foreach ($files as $file) {
            if (filectime($file) < $time) {
                unlink($file);
            }
        }
    }
}
