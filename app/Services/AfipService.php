<?php

namespace App\Services;

use Afip;

class AfipService
{
    protected $afip;

    public function __construct()
    {
        $this->afip = new Afip([
            'CUIT' => env('AFIP_CUIT'),
            'production' => env('AFIP_ENV') === 'dev',
            'cert' => env('AFIP_CERT_PATH'),
            'key' => env('AFIP_KEY_PATH'),
        ]);
    }

    public function getLastVoucher($pointOfSale, $voucherType)
    {
        return $this->afip->ElectronicBilling->GetLastVoucher($pointOfSale, $voucherType);
    }

    public function createInvoice($data)
    {
        $lastVoucher = $this->afip->ElectronicBilling->GetLastVoucher(1, 6);

        $invoice = [
           'CantReg' => 1,
            'PtoVta' => 1,
            'CbteTipo' => 6, 
            'Concepto' => 1, 
            'DocTipo' => 99,
            'DocNro' => 0, // Usa $data correctamente
            'CbteDesde' => $lastVoucher + 1,
            'CbteHasta' => $lastVoucher + 1,
            'CbteFch' => date('Ymd'),
            'ImpTotal' => $data->total_price,
            'ImpTotConc' => 0,
            'ImpNeto' => round($data->total_price / 1.21, 2),
            'ImpOpEx' => 0,
            'ImpIVA' => round($data->total_price - ($data->total_price / 1.21), 2),
            'ImpTrib' => 0,
            'MonId' => 'PES',
            'MonCotiz' => 1,
            'Iva' => [
            [
                'Id' => 5, 
                'BaseImp' => round($data->total_price / 1.21, 2), // Base imponible redondeada a 2 decimales
                'Importe' => round($data->total_price - ($data->total_price / 1.21), 2), // Importe IVA redondeado a 2 decimales
            ]
        ],
    ];
        try {
            $res = $this->afip->ElectronicBilling->CreateNextVoucher($invoice);
            return $res;
        } catch (\Exception $e) {
            throw new \Exception('Error al generar la factura: ' . $e->getMessage());
        }
    }

}
