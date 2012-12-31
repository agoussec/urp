<?php

namespace Agoussec\URP\Console;

use Illuminate\Console\Command;
use Agoussec\URP\Models\Permission;

class AddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:add {permissionname} {slug?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new permission.';

    protected $permissionname;

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
        $this->permissionname = $this->argument('permissionname');
        $this->slug = $this->argument('slug') ?? null;

        $this->info('Creating permission...');

        $this->checkSlug();

        $this->validate($this->permissionname);

        $this->info('Finally saving...');
        $permission = $this->save();

        $this->info('User permission successfully created.');
        $headers = ['Name', 'Slug'];

        $this->table($headers, [[$permission->name, $permission->slug]]);
    }

    protected function checkSlug(){
        if($this->slug){
            $this->info('Validating...');
            $this->validate($this->permissionname);
        } else {
            // create new slug using permissionname
            $this->info('Creating permission slug...');
            $this->slug = $this->createSlug();
        }
    }

    protected function save(){
        return Permission::create([
            'name' => $this->permissionname,
            'slug' => $this->slug
        ]);
    }

    protected function createSlug(){
        $string = $this->permissionname; $separator = '-';
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
        if( Permission::where('slug', $this->slug)->count()){
            $this->error('Given Slug already taken!');
            $this->slug = $this->ask('Enter custom slug for above permission...');
            $this->validate();
        }
        return true;
    }
}
