<?php

/** @author shamsh parvez */
namespace Agoussec\URP\Console;

use Illuminate\Console\Command;
use Agoussec\URP\Models\Permission;

class ListPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List All Available user Permissions.';

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
        $headers = ['Name', 'Slug'];

        $roles = Permission::all(['name', 'slug'])->toArray();

        $this->table($headers, $roles);

    }
}
