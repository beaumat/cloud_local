<?php

namespace App\Services;

use App\Models\TaxCredit;
use App\Models\TaxCreditInvoices;

class TaxCreditServices
{

    private $object;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {
        $this->object = $objectServices;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function Get(int $ID)
    {
        return TaxCredit::where('ID', $ID)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $EWT_ID,
        float $EWT_RATE,
        int $EWT_ACCOUNT_ID,
        int $LOCATION_ID,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID
    ): int {

        $ID  = (int) $this->object->ObjectNextID('TAX_CREDIT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('TAX_CREDIT');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        TaxCredit::create([
            'ID'                        => $ID,
            'RECORDED_ON'               => $this->dateServices->Now(),
            'CODE'                      => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                      => $DATE,
            'CUSTOMER_ID'               => $CUSTOMER_ID,
            'EWT_ID'                    => $EWT_ID,
            'EWT_RATE'                  => $EWT_RATE,
            'EWT_ACCOUNT_ID'            => $EWT_ACCOUNT_ID > 0 ? $EWT_ACCOUNT_ID : null,
            'LOCATION_ID'               => $LOCATION_ID,
            'AMOUNT'                    => 0,
            'NOTES'                     => $NOTES,
            'STATUS'                    => 0,
            'STATUS_DATE'               => $this->dateServices->NowDate(),
            'ACCOUNTS_RECEIVABLE_ID'    => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null
        ]);

        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        int $EWT_ID,
        float $EWT_RATE,
        int $EWT_ACCOUNT_ID,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID
    ) {

        TaxCredit::where('ID', '=', $ID)
            ->update([
                'ID'                        => $ID,
                'RECORDED_ON'               => $this->dateServices->Now(),
                'CODE'                      => $CODE,
                'EWT_ID'                    => $EWT_ID,
                'EWT_RATE'                  => $EWT_RATE,
                'EWT_ACCOUNT_ID'            => $EWT_ACCOUNT_ID > 0 ? $EWT_ACCOUNT_ID : null,
                'NOTES'                     => $NOTES,
                'ACCOUNTS_RECEIVABLE_ID'    => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null
            ]);
    }

    public function Delete(int $ID)
    {
        TaxCreditInvoices::where('TAX_CREDIT_ID', '=', $ID)->delete();
        TaxCredit::where('ID', '=', $ID)->delete();
    }

    public function Search($search, int $LOCATION_ID, int $perPage)
    {

        $result = TaxCredit::query()
            ->select([
                'tax_credit.ID',
                'tax_credit.CODE',
                'tax_credit.DATE',
                'tax_credit.AMOUNT',
                'tax_credit.NOTES',
                'tax_credit.EWT_RATE',
                'c.PRINT_NAME_AS as NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS'

            ])
            ->join('contact as c', 'c.ID', '=', 'tax_credit.CUSTOMER_ID')
            ->join('account as a', 'a.ID', '=', 'tax_credit.EWT_ACCOUNT_ID')
            ->join('document_status_map as s', 's.ID', '=', 'tax_credit.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'tax_credit.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('tax as t', 't.ID', '=', 'tax_credit.EWT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('tax_credit.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('tax_credit.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('tax_credit.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tax_credit.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        TaxCredit::where('ID', '=', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate()
            ]);
    }
    public function StoreInvoice(int $TAX_CREDIT_ID, int $INVOICE_ID, float $AMOUNT_WITHHELD, int $ACCOUNTS_RECEIVABLE_ID): int
    {

        $ID  = (int) $this->object->ObjectNextID('TAX_CREDIT_INVOICES');
        TaxCreditInvoices::create(
            [
                'ID'                        => $ID,
                'TAX_CREDIT_ID'             => $TAX_CREDIT_ID,
                'INVOICE_ID'                => $INVOICE_ID,
                'AMOUNT_WITHHELD'           => $AMOUNT_WITHHELD,
                'ACCOUNTS_RECEIVABLE_ID'    => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null
            ]
        );

        return $ID;
    }
    public function UpdateInvoice(int $ID, int $TAX_CREDIT_ID, int $INVOICE_ID, float $AMOUNT_WITHHELD)
    {

        TaxCreditInvoices::where('ID', '=', $ID)
            ->where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->update(
                [
                    'AMOUNT_WITHHELD'           => $AMOUNT_WITHHELD
                ]
            );
    }
    public function TaxCreditInvoiceExists(int $TAX_CREDIT_ID, int $INVOICE_ID): bool
    {
        return TaxCreditInvoices::where('TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->exists();
    }
    public function DeleteInvoice(int $ID)
    {
        TaxCreditInvoices::where('ID', '=', $ID)->delete();
    }

    public function GetInvoiceist(int $TAX_CREDIT_ID)
    {

        $result = TaxCreditInvoices::query()
            ->select([
                'tax_credit_invoices.ID',
                'tax_credit_invoices.INVOICE_ID',
                'tax_credit_invoices.AMOUNT_WITHHELD',
                'i.CODE',
                'i.DATE',
                'i.AMOUNT as ORG_AMOUNT',
                'i.TAXABLE_AMOUNT',
                'i.BALANCE_DUE'

            ])
            ->join('invoice as i', 'i.ID', '=', 'tax_credit_invoices.INVOICE_ID')
            ->where('tax_credit_invoices.TAX_CREDIT_ID', '=', $TAX_CREDIT_ID)
            ->get();

        return $result;
    }
}
