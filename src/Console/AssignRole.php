<?php

namespace Agoussec\URP\Console;

use Illuminate\Console\Command;
use Agoussec\URP\Models\Permission;
use Agoussec\URP\Models\Role;

class AssignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:role {roleslug} {usermail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role to specific user. Param: 1- Role slug, 2- User email';

    protected $role;

    protected $user;
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

        $roleslug = $this->argument('roleslug');
        $userslug = $this->argument('usermail');

        $this->role = $this->getRole($roleslug);
        if(!$this->role){
            $this->error('Given role slug not found!');
            return;
        }

        $this->user = $this->getUser($userslug);
        if(!$this->user){
            $this->error('Given user email not found!');
            return;
        }

        if($this->user->hasRole($roleslug)){
            $this->info($userslug.' already has '.$roleslug.' role!');
            return;
        }

        if($this->role && $this->user){
            $this->attachRole();
            $this->info($roleslug.' role  successfully attached to '.$userslug);
            return;
        }


    }

    protected function attachRole(){
        return $this->user->roles()->attach($this->role);
    }

    protected function getRole($roleslug){
        return Role::where('slug',  $roleslug)->first();
    }

    protected function getUser($userslug){
        return config("auth.providers.users.model")::where('email', $userslug)->first();
    }
}
