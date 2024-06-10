<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Commands;

use Illuminate\Console\Command;

class SparkcommerceMultivendorRestRoutesCommand extends Command
{
    public $signature = 'sparkcommerce-multivendor-rest-routes';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
