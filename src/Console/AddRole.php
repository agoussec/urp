<?php

namespace Agoussec\URP\Console;

use Illuminate\Console\Command;
use Agoussec\URP\Models\Role;

class AddRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:add {rolename} {slug?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new user role in project.';

    protected $rolename;

    protected $slug;

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
        $this->rolename = $this->argument('rolename');
        $this->slug = $this->argument('slug') ?? null;

        $this->info('Creating role...');

        $this->checkSlug();

        $this->validate($this->rolename);

        $this->info('Finally saving...');
        $role = $this->save();

        $this->info('User role successfully created.');
        $headers = ['Name', 'Slug'];

        $this->table($headers, [[$role->name, $role->slug]]);
    }

    protected function checkSlug(){
        if($this->slug){
            $this->info('Validating...');
            $this->validate($this->rolename);
        } else {
            // create new slug using rolename
            $this->info('Creating role slug...');
            $this->slug = $this->createSlug();
        }
    }

    protected function save(){
        return Role::create([
            'name' => $this->rolename,
            'slug' => $this->slug
        ]);
    }

    protected function createSlug(){
        $string = $this->rolename; $separator = '-';
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = array( '&' => 'and', "'" => '');
        $string = mb_strtolower( trim( $string ), 'UTF-8' );
        $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
        $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);
        return $string;
    }

    protected function validate(){
        if( Role::where('slug', $this->slug)->count()){
            $this->error('Given Slug already taken!');
            $this->slug = $this->ask('Enter custom slug for above role...');
            $this->validate();
        }
        return true;
    }
}
