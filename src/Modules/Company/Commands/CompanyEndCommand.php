<?php

namespace Modules\Company\Commands;

use Illuminate\Console\Command;

class CompanyEndCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Окончание срока активности компании';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dd('im here');
    }
}
