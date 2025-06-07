<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;

class NewComponent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'melorain:make {name} {--title=} {--all=no} {--crud=no} {--controller=no} {--model=no} {--table=no} {--route=no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new base component';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $title = $this->option('title');
        $all = $this->option('all');
        $crud = $this->option('crud');
        $controller = $this->option('controller');
        $model = $this->option('model');
        $table = $this->option('table');
        $route = $this->option('route');

        if(is_null($name)){
            $this->error('>> Please enter a name for new component.');
            return false;
        }
        if(is_null($title)){
            $this->error('>> Please enter a title for new component.');
            return false;
        }

        $component_name_single =$name;
        if(substr($component_name_single,-1) == 'y'){
            $component_name =substr($component_name_single,0,(strlen($component_name_single)-1)).'ies';
        } else {
            $component_name =$component_name_single.'s';
        }
        $component_name_controller = ucfirst($component_name_single);
        $component_title =$title;

        $this->line('MeloRain Component Maker 0.1');
        $this->line('Component: '.$name);

        if($all == 'yes' || $crud == 'yes') {
            $this->create_index($component_name,$component_name_single,$component_name_controller,$component_title);
            $this->create_edit($component_name,$component_name_single,$component_name_controller,$component_title);
            $this->create_create($component_name,$component_name_single,$component_name_controller,$component_title);
        }
        if($all == 'yes' || $controller == 'yes') {
            $this->create_controller($component_name,$component_name_single,$component_name_controller,$component_title);
        }
        if($all == 'yes' || $model == 'yes') {
            $this->create_model($component_name,$component_name_single,$component_name_controller,$component_title);
        }
        if($all == 'yes' || $route == 'yes') {
            $this->create_route_admin($component_name,$component_name_single,$component_name_controller,$component_title);
            $this->create_route_web($component_name,$component_name_single,$component_name_controller,$component_title);
        }
        if($all == 'yes' ||  $table== 'yes') {
            $this->create_sql($component_name,$component_name_single,$component_name_controller,$component_title);
        }

    }
    function create_index($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= index.blade.php =============================
        //
        $code_index = file_get_contents('./resources/artisan/create_component/source/crud/index.blade.php');
        $code_index = $this->replaceTags($code_index,$component_name,$component_name_single,$component_name_controller,$component_title);
        if ( !file_exists( './resources/views/admin/'.$component_name ) && !is_dir( './resources/views/admin/'.$component_name )) {
            mkdir( './resources/views/admin/'.$component_name );
        }
        $index = file_put_contents('./resources/views/admin/'.$component_name.'/index.blade.php',$code_index);
        if($index){
            $this->info('>> index.blade.php was created successful.');
        } else {
            $this->error('>> Error to create index.blade.php');
        }
    }
    function create_edit($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= edit.blade.php =============================
        //
        $code_edit = file_get_contents('./resources/artisan/create_component/source/crud/edit.blade.php');
        $code_edit = $this->replaceTags($code_edit,$component_name,$component_name_single,$component_name_controller,$component_title);

        if ( !file_exists( './resources/views/admin/'.$component_name ) && !is_dir( './resources/views/admin/'.$component_name ) ) {
            mkdir( './resources/views/admin/'.$component_name );
        }
        $edit = file_put_contents('./resources/views/admin/'.$component_name.'/edit.blade.php',$code_edit);
        if($edit){
            $this->info('>> edit.blade.php was created successful.');
        } else {
            $this->error('>> Error to create edit.blade.php');
        }
    }
    function create_create($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= create.php =============================
        //
        $code_create = file_get_contents('./resources/artisan/create_component/source/crud/create.blade.php');
        $code_create = $this->replaceTags($code_create,$component_name,$component_name_single,$component_name_controller,$component_title);

        if ( !file_exists( './resources/views/admin/'.$component_name ) && !is_dir( './resources/views/admin/'.$component_name ) ) {
            mkdir( './resources/views/admin/'.$component_name );
        }
        $create = file_put_contents('./resources/views/admin/'.$component_name.'/create.blade.php',$code_create);
        if($create){
            $this->info('>> create.blade.php was created successful.');
        } else {
            $this->error('>> Error to create create.blade.php');
        }
    }
    function create_controller($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= XController.php =============================
        //
        $code_controller = file_get_contents('./resources/artisan/create_component/source/controller/controller.php');
        $code_controller = $this->replaceTags($code_controller,$component_name,$component_name_single,$component_name_controller,$component_title);

        if ( !file_exists( './app/Http/Controllers/' ) && !is_dir( 'export/copy_as_root/app/Http/Controllers/' ) ) {
            mkdir( './app/Http/Controllers/' );
        }
        $controller = file_put_contents('./app/Http/Controllers/'.$component_name_controller.'Controller.php',$code_controller);
        if($controller){
            $this->info('>> '.$component_name_controller.'Controller.php was created successful.');
        } else {
            $this->error('>> Error to create '.$component_name_controller.'Controller.php');
        }
    }
    function create_model($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= Model.php =============================
        //
        $code_model = file_get_contents('./resources/artisan/create_component/source/model/model.php');
        $code_model = $this->replaceTags($code_model,$component_name,$component_name_single,$component_name_controller,$component_title);

        if ( !file_exists( './app/Models/' ) && !is_dir( './app/Models/' ) ) {
            mkdir( './app/Models/' );
        }
        $model = file_put_contents('./app/Models/'.$component_name_controller.'.php',$code_model);
        if($model){
            $this->info('>> '.$component_name_controller.'.php was created successful.');
        } else {
            $this->error('>> Error to create '.$component_name_controller.'.php');
        }
    }
    function create_route_admin($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= route =============================
        //
        $code_route = file_get_contents('./resources/artisan/create_component/source/route/route_admin.php');
        $code_route = $this->replaceTags($code_route,$component_name,$component_name_single,$component_name_controller,$component_title);


        $route = file_put_contents('./routes/admin.php',$code_route,FILE_APPEND);
        if($route){
            $this->info('>> /routes/admin.php was append successful.');
        } else {
            $this->error('>> Error to append /routes/admin.php');
        }
    }
    function create_route_web($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= route =============================
        //
        $code_route = file_get_contents('./resources/artisan/create_component/source/route/route_web.php');
        $code_route = $this->replaceTags($code_route,$component_name,$component_name_single,$component_name_controller,$component_title);


        $route = file_put_contents('./routes/web.php',$code_route,FILE_APPEND);
        if($route){
            $this->info('>> /routes/web.php was append successful.');
        } else {
            $this->error('>> Error to append /routes/web.php');
        }
    }
    function create_sql($component_name,$component_name_single,$component_name_controller,$component_title){
        //
        //============================= sql =============================
        //
        // database credentials
        $mysql_host = "localhost";
        $mysql_database = "cms";
        $mysql_user = "root";
        $mysql_password = "";

        $code_sql = file_get_contents('./resources/artisan/create_component/source/sql/table.sql');
        $code_sql = $this->replaceTags($code_sql,$component_name,$component_name_single,$component_name_controller,$component_title);

        $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
        // prepare the SQL statements
        $stmt = $db->prepare($code_sql);
        // execute the SQL
        if ($stmt->execute()){
            $this->info('>> Table '.$component_name.' was append successful.');
        } else {
            $this->error('>> Error to create '.$component_name);
        }
    }
    function replaceTags($str,$component_name,$component_name_single,$component_name_controller,$component_title){
        $str = str_replace('#*#',"'",$str);
        $str = str_replace('#**#','"',$str);
        $str = str_replace('[[component_name]]',$component_name,$str);
        $str = str_replace('[[component_name_single]]',$component_name_single,$str);
        $str = str_replace('[[component_name_controller]]',$component_name_controller,$str);
        $str = str_replace('[[component_title]]',$component_title,$str);
        $str = str_replace('[[date]]',date('Y-m-d H:i:s'),$str);
        return $str;
    }
}
