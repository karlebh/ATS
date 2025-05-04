<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ATSSymbolicLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ats:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Symbolic link in Cpanel live server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $link = public_path('storage');
        $storageFolder = storage_path('app/public');

        if (is_link($link)) {
            return $this->info("Symbolic link already exist.");
        }

        if (! symlink(
            $storageFolder,
            $link
        )) {
            return $this->info("Could not create symbolic link");
        }

        //    $sereverCommand = ln -s "\home\atsjobmanager\ATS\storage\app\public" "\home\atsjobmanager\public_html"; 
        //    ls -l /home/atsjobmanager/

    }
}
