<?php

/************************************
 Entry point of the project.
 To be run from the command line.
 ************************************/

echo sprintf("Starting...\n");

define('SQL_HOST', 'mariadb');
define('SQL_USER', 'root');
define('SQL_PWD', 'root');
define('SQL_DB', 'cmc_db');
define('RESSOURCES_DIR', __DIR__ . '/../resources/');


spl_autoload_register(function (string $classname) {
    $classname = str_replace("\\", "/", $classname);
    include_once(__DIR__ . '/' . $classname . '.php');
});

class App {
    public function import() { /* import jobs from all .xml files */
        $files = scandir(RESSOURCES_DIR);
        foreach($files as $file) {
            if (strpos($file, '.xml') !== false) {
                $jobsImporter = new JobsImporter(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB, RESSOURCES_DIR . $file);
                $count = $jobsImporter->importJobs();
            }
        }
        
        echo sprintf("> %d jobs imported.\n", $count);
    }
    
    public function listJobs() { /* list jobs */
        $jobsLister = new JobsLister(SQL_HOST, SQL_USER, SQL_PWD, SQL_DB);
        $jobs = $jobsLister->listJobs();
        
        echo sprintf("> all jobs (%d):\n", count($jobs));
        foreach ($jobs as $job) {
            echo sprintf(" %d: %s - %s - %s\n", $job['id'], $job['reference'], $job['title'], $job['publication']);
        }
    }
}

$app = new App();

$app->import();
$app->listJobs();

echo sprintf("Done.\n");
