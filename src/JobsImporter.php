<?php

use Models\Job;

class JobsImporter
{
    private $db;
    private $file;

    public function __construct($host, $username, $password, $databaseName, $file)
    {
        $this->file = $file;
        
        /* connect to DB */
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
            $this->db->exec('DELETE FROM job');
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    public function importJobs()
    {
        $xml = simplexml_load_file($this->file); /* parse XML file */
        $count = 0; /* import each item */
        $jobs = array();

        if (strpos($this->file, 'regionsjob.xml') !== false) {
            foreach ($xml->item as $item) {
                $jobs[] = new Job(
                    addslashes($item->ref),
                    addslashes($item->title),
                    addslashes($item->description),
                    addslashes($item->url),
                    addslashes($item->company),
                    addslashes($item->pubDate)
                );
            }
        }
        if (strpos($this->file, 'jobteaser.xml') !== false) {
            foreach ($xml->offer as $item) {
                $jobs[] = new Job(
                    addslashes($item->reference),
                    addslashes($item->title),
                    addslashes($item->description),
                    addslashes($item->link),
                    addslashes($item->companyname),
                    addslashes($item->publisheddate)
                );
            }
        }
        try {
            $query = $this->db->prepare('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (?, ?, ?, ?, ?, ?)');
			$this->db->beginTransaction();
            foreach ($jobs as $job) {
                $query->bindValue(1, $job->getRef());
                $query->bindValue(2, $job->getTitle());
                $query->bindValue(3, $job->getDesc());
                $query->bindValue(4, $job->getLink());
                $query->bindValue(5, $job->getCompany());
                $query->bindValue(6, $job->getDate());
                $query->execute();
                $count++;
            }
            $this->db->commit();
        } catch (Exception $e) {
            die('Import ERROR: ' . $e->getMessage() . '\n');
        }
        return $count;
    }
}
