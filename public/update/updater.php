<?php
use Illuminate\Support\Facades\Auth;

require '../../vendor/autoload.php';
$app = require_once  '../../bootstrap/app.php';
$request = Illuminate\Http\Request::capture();
$request->setMethod('GET');
$app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
$request = $app['request'];
$isAuthorized = Auth::check();
//echo Auth::user()->email;
if(!$isAuthorized){
    header('HTTP/1.1 403 Forbidden');
    exit;
}
?>
<html>
    <head>
        <title>
            MeloRain updater
        </title>
    </head>
    <body>


<?php
// database credentials
$mysql_host = "localhost";
$mysql_database = "purplebutterfly_cms";
$mysql_user = "purplebutterfly_cmsuser";
$mysql_password = "iHuuu5e2c*y;";

function progress($count,$all){
    $c = round(($count*100)/$all);
    return $c;
}

if(isset($_POST)){
    if(isset($_POST['db_file'])){
        $sql = $_POST['db_file'];
        // database connection string
        $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
        // get data from the SQL file
        $query = file_get_contents($sql);
        // prepare the SQL statements
        $stmt = $db->prepare($query);
        // execute the SQL
        if ($stmt->execute()){
            echo '<div style="color:green;padding:5px;background: #bedbcb">SQL execute successful!</div>';
        }
        else {
            echo '<div style="color:red;padding:5px;background: yellow">SQL execute Fail</div>';
        }
    }
    if(isset($_POST['file'])){
        //------------------------------
        $file_counts = count($_POST['file']);
//        $progress = 0;
//        echo round((3*100)/$file_counts);
//        exit;
        //------------------------------
        $cc=1;
        foreach ($_POST['file'] as $file){
            $target = substr($file,10,strlen($file));
            echo '<div style="padding:5px;border:1px #999 solid;background: #efefef">';
            if (!copy($file, '../../'.$target)) {
                echo "failed to copy $file... <span style='color:red;font-weight: bold;'>[ ".progress($cc,$file_counts)."% ]</span><br/>";
            } else {
                echo "copy ($file) to (./$target) <span style='color:green;font-weight: bold'>[ ".progress($cc,$file_counts)."% ]</span><br/>";
            }
            echo '</div>';
            $cc++;
        }
    }
    //exit;
}
if(!isset($_POST['file']) && !isset($_POST['db_file'])){

$zip = new ZipArchive;
$path = [];
if ($zip->open('u.zip') === TRUE) {
    $zip->extractTo('ext/');
    $zip->close();
    //echo 'ok';
    $all = [];
    foreach (array_filter(glob('ext/*'), 'is_dir') as $dir1) {
        //echo $dir1, PHP_EOL;
        foreach (array_filter(glob($dir1.'/*'), 'is_file') as $file1) {
            //echo $file1, PHP_EOL;
            array_push($all,$file1);
        }
        foreach (array_filter(glob($dir1.'/*'), 'is_dir') as $dir2) {
            //echo $dir2, PHP_EOL;
            foreach (array_filter(glob($dir2.'/*'), 'is_file') as $file2) {
                //echo $file2, PHP_EOL;
                array_push($all,$file2);
            }
            foreach (array_filter(glob($dir2.'/*'), 'is_dir') as $dir3) {
                //echo $dir3, PHP_EOL;
                foreach (array_filter(glob($dir3.'/*'), 'is_file') as $file3) {
                    //echo $file3, PHP_EOL;
                    array_push($all,$file3);
                }
                foreach (array_filter(glob($dir3.'/*'), 'is_dir') as $dir4) {
                    //echo $dir4, PHP_EOL;
                    foreach (array_filter(glob($dir4.'/*'), 'is_file') as $file4) {
                        //echo $file4, PHP_EOL;
                        array_push($all,$file4);
                    }
                }
            }
        }
    }
    //print_r($all);

} else {
    //echo 'failed';
}

?>
<style>
    table tr td{
        border:1px #888 solid;
        padding:5px;
    }
</style>
<form action="updater.php" method="POST">
    <table>
        <tr>
            <td style="font-weight: bold">
                Source
            </td>
            <td style="font-weight: bold">
                Target
            </td>
        </tr>
        <?php
        foreach ($all as $file){
            ?>
            <tr>
                <td style="font-family: 'Courier New'">
                    <?=$file;?>
                </td>
                <td style="font-family: 'Courier New'">
                    <?php
                    $e = explode('.',$file);
                    if(end($e) == 'sql'){
                        ?>
                        <input type="hidden" name="db_file" value="<?=$file;?>" />
                        <span style="color: red">
                        Execute in DB.
                    </span>
                        <?php
                    } else {
                        ?>
                        <input type="hidden" name="file[]" value="<?=$file;?>" />
                        /<?=substr($file,4,strlen($file));?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td>
                <input type="submit" value="Start">
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
    </table>
</form>
<?php
}
?>
    </body>
</html>


