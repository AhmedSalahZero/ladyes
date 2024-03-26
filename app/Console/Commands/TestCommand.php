<?php

namespace App\Console\Commands;

use App\Models\CarSize;
use App\Models\City;
use App\Models\Travel;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
		$carSize = CarSize::first();
		$travel = Travel::first();
		/**
		 * @var Travel $travel ;
		 */
		$travel->calculateClientActualPriceWithoutDiscount();
    }
   
}
