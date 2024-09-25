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
            'production' => env('AFIP_ENV') === 'production',
            'cert' => env('AFIP_CERT_PATH'),
            'key' => env('AFIP_KEY_PATH'),
        ]);
    }

    // Obtener el último número de comprobante
    public function getLastVoucher($pointOfSale, $voucherType)
    {
        return $this->afip->ElectronicBilling->GetLastVoucher($pointOfSale, $voucherType);
    }

    // Crear factura
    /*public function createInvoice($invoiceData)
    {
        return $this->afip->ElectronicBilling->CreateVoucher($invoiceData);
    }*/

    public function createInvoice($data)
    {
        $lastVoucher = $this->afip->ElectronicBilling->GetLastVoucher(config('services.afip.punto_venta'), 6);

        $invoice = [
            'CantReg' => 1,
            'PtoVta' => config('services.afip.punto_venta'),
            'CbteTipo' => 6, // Factura B
            'Concepto' => 1,
            'DocTipo' => 96, // DNI
            'DocNro' => $data['dni'],
            'CbteDesde' => $lastVoucher + 1,
            'CbteHasta' => $lastVoucher + 1,
            'CbteFch' => date('Ymd'),
            'ImpTotal' => $data['total'],
            'ImpNeto' => $data['net'],
            'ImpIVA' => $data['iva'],
            'ImpTrib' => 0,
            'MonId' => 'PES',
            'MonCotiz' => 1,
            'Iva' => [
                [
                    'Id' => 5, // 21% IVA
                    'BaseImp' => $data['net'],
                    'Importe' => $data['iva']
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
