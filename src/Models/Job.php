<?php

namespace Models;

class Job {
    private $ref;
    private $title;
    private $desc;
    private $link;
    private $company;
    private $date;

    public function __construct($ref, $title, $desc, $link, $company, $date) {
        $this->ref = $ref;
        $this->title = $title;
        $this->desc = $desc;
        $this->link = $link;
        $this->company = $company;
        $this->date = $date;
    }

    public function getRef() {
        return $this->ref;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDesc() {
        return $this->desc;
    }

    public function getLink() {
        return $this->link;
    }

    public function getCompany() {
        return $this->company;
    }

    public function getDate() {
        return $this->date;
    }
}
