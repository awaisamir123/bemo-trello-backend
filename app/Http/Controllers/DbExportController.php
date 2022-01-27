<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DbExportController extends Controller
{
    public function export()
    {
//        $dbName = config('database.connections.mysql.database');
//        $dbUser = config('database.connections.mysql.username');
//        $dbPass = config('database.connections.mysql.password');

        //ENTER THE RELEVANT INFO BELOW
        $mysqlHostName = env('DB_HOST');
        $mysqlUserName = env('DB_USERNAME');
        $mysqlPassword = env('DB_PASSWORD');
        $DbName = env('DB_DATABASE');
        $backup_name = "mybackup.sql";
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();


        $output = '';
        foreach ($tables as $table) {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";
            $statement = $connect->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();

            foreach ($show_table_result as $show_table_row) {
                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table . "";
            $statement = $connect->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();

            for ($count = 0; $count < $total_row; $count++) {
                $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                $table_column_array = array_keys($single_result);
                $table_value_array = array_values($single_result);
                $output .= "\nINSERT INTO $table (";
                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file_name = 'db_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);


//        MySql::create()
//            ->setDbName($dbName)
//            ->setUserName($dbUser)
//            ->setPassword($dbPass)
//            ->dumpToFile('test1.sql');
//        dd('sd');


//        $dumpCommand = MySql::create()
//            ->setUserName($dbUser)
//            ->setPassword($dbPass)
//            ->addExtraOption('--all-databases')
//            ->getDumpCommand('testing1.sql', 'credentials.txt');
//dd(exec($dumpCommand));

//        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
//        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
//        $returnVar = NULL;
//        $output  = NULL;
//        exec($command, $output, $returnVar);
//

//
//
//        exec("mysqldump $dbName --user=$dbUser --password=$dbPass > /fileName.sql", $errors);
//
//        if ( ! empty($errors)) {
//            dd($errors);
//        }
//
//        dd('after if');
    }
}
