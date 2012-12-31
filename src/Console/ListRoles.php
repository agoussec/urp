<?php

namespace Agoussec\URP\Console;

use Illuminate\Console\Command;
use Agoussec\URP\Models\Role;

class ListRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List All Available user roles.';

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

        $roles = Role::all(['name', 'slug'])->toArray();

        $this->table($headers, $roles);

    }
}
