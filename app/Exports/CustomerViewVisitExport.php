<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerViewVisitExport implements FromCollection
{
    protected $data;
    protected $username;

    public function __construct($data, $username)
    {
        $this->data = $data;
        $this->username = $username;
    }

    public function collection()
    {
        return $this->data;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
