<?php

namespace App\Console\Commands;

use App\Entities\ActivatedOffer\Models\ActivatedOffer;
use App\Entities\Offer\Models\Offer;
use Illuminate\Console\Command;

class RemoveOldTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offer:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove offer';

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
        $this->info('Start');

        $offers = Offer::where('end_at', '<', now())->get();
        foreach ($offers as $offer) {
            $mobileUsers = $offer->mobileUsers;
            foreach ($mobileUsers as $mobileUser) {
                if (!ActivatedOffer::where('offer_id', $offer->id)->where('mobile_user_id', $mobileUser->id)->exists()) {
                    ActivatedOffer::create(
                        [
                            'mobile_user_id' => $mobileUser->id,
                            'offer_id' => $offer->id,
                            'type' => 'time'
                        ]
                    );
                }

                $mobileUser->offers()->detach($offer->id);
            }
        }

        $this->info('End');
    }
}
