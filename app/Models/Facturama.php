<?php
namespace App\Models;
use Illuminate\Support\Facades\Http;

class Facturama
{
    public function createInvoice($param)
    {

    }

    private function getClient()
    {
        return Http::withBasicAuth();
    }
}