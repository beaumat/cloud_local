<?php

namespace App\Services;

use App\Models\AccountJournal;
use Illuminate\Support\Facades\DB;

class AccountJournalServices
{
    public string $TX_PO = '
    CASE
        WHEN o.`ID` = 2     THEN ( select bill.`CUSTOM_FIELD1` from bill  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select bill.`CUSTOM_FIELD1` from bill_items  join bill on bill.ID = bill_items.BILL_ID  where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select bill.`CUSTOM_FIELD1` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select credit_memo.`CUSTOM_FIELD1` from credit_memo where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select credit_memo.`CUSTOM_FIELD1` from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select credit_memo.`CUSTOM_FIELD1` from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment.`CUSTOM_FIELD1` from inventory_adjustment where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment.`CUSTOM_FIELD1` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select invoice.`PO_NUMBER` from invoice where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select invoice.`PO_NUMBER` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( select stock_transfer.`CUSTOM_FIELD1` from stock_transfer where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE   )
        WHEN o.`ID` = 39    THEN ( select stock_transfer.`CUSTOM_FIELD1` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )
        WHEN o.`ID` = 41    THEN ( select payment.`RECEIPT_REF_NO` from payment where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select payment.`RECEIPT_REF_NO` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select sales_receipt.`PAYMENT_REF_NO` from `sales_receipt`  where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select `sales_receipt`.`PAYMENT_REF_NO` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select `check`.`CUSTOM_FIELD1` from `check`  where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select `check`.`CUSTOM_FIELD1` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select `check`.`CUSTOM_FIELD1` from `check_items` join `check` on check.ID = check_items.CHECK_ID  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 79    THEN ( select `check`.`CUSTOM_FIELD1` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 59    THEN ( select bill_credit.`CUSTOM_FIELD1` from bill_credit  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select bill_credit.`CUSTOM_FIELD1` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID  where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select bill_credit.`CUSTOM_FIELD1` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`CASH_BACK_NOTES` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`CASH_BACK_NOTES` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select general_journal.`NOTES` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`CODE` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select build_assembly.`CUSTOM_FIELD1` from build_assembly where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select build_assembly.`CUSTOM_FIELD1` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 72    THEN ( select  0  as `CODE` from tax_credit where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select  0  as `CODE` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 113    THEN ( select pull_out.`CUSTOM_FIELD1` from pull_out where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114    THEN ( select pull_out.`CUSTOM_FIELD1` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )
 
        END as TX_PO';

    public string $TX_CODE = '
    CASE
        WHEN o.`ID` = 2     THEN ( select bill.`CODE` from bill  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select bill.`CODE` from bill_items  join bill on bill.ID = bill_items.BILL_ID  where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select bill.`CODE` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select credit_memo.`CODE` from credit_memo where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select credit_memo.`CODE` from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select credit_memo.`CODE` from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment.`CODE` from inventory_adjustment where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment.`CODE` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select invoice.`CODE` from invoice where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select invoice.`CODE` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( select stock_transfer.`CODE` from stock_transfer where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE   )
        WHEN o.`ID` = 39    THEN ( select stock_transfer.`CODE` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )
        WHEN o.`ID` = 41    THEN ( select payment.`CODE` from payment where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select payment.`CODE` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select sales_receipt.`CODE` from `sales_receipt`  where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select `sales_receipt`.`CODE` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select `check`.`CODE` from `check`  where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select `check`.`CODE` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select `check`.`CODE` from `check_items` join `check` on check.ID = check_items.CHECK_ID  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 79    THEN ( select `check`.`CODE` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 59    THEN ( select bill_credit.`CODE` from bill_credit  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select bill_credit.`CODE` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID  where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select bill_credit.`CODE` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`CODE` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`CODE` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select general_journal.`CODE` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`CODE` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select build_assembly.`CODE` from build_assembly where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select build_assembly.`CODE` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 72    THEN ( select tax_credit.`CODE` from tax_credit where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select tax_credit.`CODE` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 113   THEN ( select pull_out.`CODE` from pull_out where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114   THEN ( select pull_out.`CODE` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )
        
        WHEN o.`ID` = 127   THEN ( select depreciation.`CODE` from depreciation where depreciation.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 128   THEN ( select depreciation.`CODE` from depreciation_items join depreciation on depreciation.ID = depreciation_items.DEPRECIATION_ID where depreciation_items.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID )
     
        END as TX_CODE';

    public string $TX_NOTES = '
    CASE
        WHEN o.`ID` = 2     THEN ( select bill.`NOTES` from bill  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select bill.`NOTES` from bill_items  join bill on bill.ID = bill_items.BILL_ID  where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select bill.`NOTES` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select credit_memo.`NOTES` from credit_memo where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select credit_memo.`NOTES` from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select credit_memo.`NOTES` from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment.`NOTES` from inventory_adjustment where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment.`NOTES` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select invoice.`NOTES` from invoice where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select invoice.`NOTES` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( select stock_transfer.`NOTES` from stock_transfer where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )
        WHEN o.`ID` = 39    THEN ( select stock_transfer.`NOTES` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE  )
        WHEN o.`ID` = 41    THEN ( select payment.`NOTES` from payment where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select payment.`NOTES` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select sales_receipt.`NOTES` from `sales_receipt`  where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select `sales_receipt`.`NOTES` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select `check`.`NOTES` from `check`  where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select `check`.`NOTES` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select `check`.`NOTES` from `check_items` join `check` on check.ID = check_items.CHECK_ID  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 79    THEN ( select `check`.`NOTES` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 59    THEN ( select bill_credit.`NOTES` from bill_credit  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select bill_credit.`NOTES` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID  where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select bill_credit.`NOTES` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`NOTES` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`NOTES` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select general_journal.`NOTES` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`NOTES` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select build_assembly.`NOTES` from build_assembly where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select build_assembly.`NOTES` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        
        WHEN o.`ID` = 72    THEN ( select tax_credit.`NOTES` from tax_credit where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select tax_credit.`NOTES` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
      
        WHEN o.`ID` = 113    THEN ( select pull_out.`NOTES` from pull_out where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114    THEN ( select pull_out.`NOTES` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )

           WHEN o.`ID` = 127   THEN ( select depreciation.`NOTES` from depreciation where depreciation.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 128   THEN ( select depreciation.`NOTES` from depreciation_items join depreciation on depreciation.ID = depreciation_items.DEPRECIATION_ID where depreciation_items.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID )
     
    
        END as TX_NOTES';



    public string $TX_NAME = '
    CASE
        WHEN o.`ID` = 2     THEN ( select contact.PRINT_NAME_AS from bill join contact on contact.ID = bill.VENDOR_ID where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select contact.PRINT_NAME_AS from bill_items join bill on bill.ID = bill_items.BILL_ID  join contact on contact.ID = bill.VENDOR_ID where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select contact.PRINT_NAME_AS from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID join contact on contact.ID = bill.VENDOR_ID where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select contact.PRINT_NAME_AS from credit_memo join contact on contact.ID = credit_memo.CUSTOMER_ID where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select contact.PRINT_NAME_AS from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID join contact on contact.ID = credit_memo.CUSTOMER_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select contact.PRINT_NAME_AS from credit_memo_items join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID join contact on contact.ID = credit_memo.CUSTOMER_ID where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment_type.`DESCRIPTION` from inventory_adjustment join inventory_adjustment_type on inventory_adjustment_type.ID = inventory_adjustment.ADJUSTMENT_TYPE_ID where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment_type.`DESCRIPTION` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID join inventory_adjustment_type on inventory_adjustment_type.ID = inventory_adjustment.ADJUSTMENT_TYPE_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select contact.PRINT_NAME_AS from invoice join contact on contact.ID = invoice.CUSTOMER_ID where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select contact.PRINT_NAME_AS from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID join contact on contact.ID = invoice.CUSTOMER_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( null)
        WHEN o.`ID` = 39    THEN ( null)
        WHEN o.`ID` = 41    THEN ( select contact.PRINT_NAME_AS from payment join contact on contact.ID = payment.CUSTOMER_ID where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select contact.PRINT_NAME_AS from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID join contact on contact.ID = payment.CUSTOMER_ID  where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select contact.PRINT_NAME_AS from `sales_receipt` join contact on contact.ID = sales_receipt.CUSTOMER_ID where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select contact.PRINT_NAME_AS from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID join contact on contact.ID = sales_receipt.CUSTOMER_ID   where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select contact.PRINT_NAME_AS from `check` join contact on contact.ID = check.PAY_TO_ID where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select contact.PRINT_NAME_AS from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  join contact on contact.ID = check.PAY_TO_ID where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select contact.PRINT_NAME_AS from `check_items` join `check` on check.ID = check_items.CHECK_ID  join contact on contact.ID = check.PAY_TO_ID where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 79    THEN ( select contact.PRINT_NAME_AS from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  join contact on contact.ID = check.PAY_TO_ID where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)    
        WHEN o.`ID` = 59    THEN ( select contact.PRINT_NAME_AS from bill_credit join contact on contact.ID = bill_credit.VENDOR_ID  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select contact.PRINT_NAME_AS from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID join contact on contact.ID = bill_credit.VENDOR_ID   where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select contact.PRINT_NAME_AS from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID join contact on contact.ID = bill_credit.VENDOR_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`NOTES` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`NOTES` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select contact.PRINT_NAME_AS from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID left outer join contact on contact.ID = general_journal.CONTACT_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`NOTES` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select item.DESCRIPTION from build_assembly join item on item.ID = build_assembly.ASSEMBLY_ITEM_ID  where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select item.DESCRIPTION from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  join  item on item.ID = build_assembly_items.ITEM_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )    
        WHEN o.`ID` = 72    THEN ( select contact.`PRINT_NAME_AS` from tax_credit left join contact on contact.ID = tax_credit.CUSTOMER_ID where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select contact.`PRINT_NAME_AS` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  left join contact on contact.ID = tax_credit.CUSTOMER_ID where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )    
        WHEN o.`ID` = 113    THEN ( select contact.`PRINT_NAME_AS` from pull_out  left join contact on contact.ID = pull_out.PREPARED_BY_ID where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114    THEN ( select contact.`PRINT_NAME_AS` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID left join contact on contact.ID = pull_out.PREPARED_BY_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )

    END as TX_NAME';



    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function DeleteJournal(
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $JOURNAL_NO,
        int $SUBSIDIARY_ID,
        int $OBJECT_ID,
        int $OBJECT_TYPE,
        string $OBJECT_DATE,
        $ENTRY_TYPE
    ) {
        $this->JournalModify(
            $ACCOUNT_ID,
            $LOCATION_ID,
            $JOURNAL_NO,
            $SUBSIDIARY_ID,
            $OBJECT_ID,
            $OBJECT_TYPE,
            $OBJECT_DATE,
            $ENTRY_TYPE,
            0,
            0,
            ''
        );
    }

    private function Update(
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $SUBSIDIARY_ID,
        int $SEQUENCE_GROUP,
        int $OBJECT_TYPE,
        int $OBJECT_ID,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        $EXTENDED_OPTIONS = null
    ) {

        if ($ACCOUNT_ID > 0) {

            AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('ACCOUNT_ID', $ACCOUNT_ID)
                ->where('ENTRY_TYPE', $ENTRY_TYPE)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->update([
                    'AMOUNT' => $AMOUNT
                ]);
        } else {
            AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->update([
                    'SEQUENCE_GROUP'    => $SEQUENCE_GROUP,
                    'ENTRY_TYPE'        => $ENTRY_TYPE,
                    'AMOUNT'            => $AMOUNT
                ]);
        }
    }
    private function Store(
        int $PREVIOUS_ID,
        int $SEQUENCE_NO,
        int $JOURNAL_NO,
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $SUBSIDIARY_ID,
        int $SEQUENCE_GROUP,
        int $OBJECT_TYPE,
        int $OBJECT_ID,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        float $ENDING_BALANCE,
        $EXTENDED_OPTIONS = null
    ) {

        $ID = (int) $this->object->ObjectNextID('ACCOUNT_JOURNAL');
        AccountJournal::create([
            'ID' => $ID,
            'PREVIOUS_ID'    => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
            'SEQUENCE_NO'    => $SEQUENCE_NO,
            'JOURNAL_NO'     => $JOURNAL_NO,
            'ACCOUNT_ID'     => $ACCOUNT_ID,
            'LOCATION_ID'    => $LOCATION_ID,
            'SUBSIDIARY_ID'  => $SUBSIDIARY_ID,
            'SEQUENCE_GROUP' => $SEQUENCE_GROUP,
            'OBJECT_TYPE'    => $OBJECT_TYPE,
            'OBJECT_ID'      => $OBJECT_ID,
            'OBJECT_DATE'    => $OBJECT_DATE,
            'ENTRY_TYPE'     => $ENTRY_TYPE,
            'AMOUNT'         => $AMOUNT,
            'ENDING_BALANCE' => $ENDING_BALANCE,
            'EXTENDED_OPTIONS' => $EXTENDED_OPTIONS
        ]);
    }
    public function getJournalNo(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select(['JOURNAL_NO'])
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();

        if ($data) { // if exists
            return (int) $data->JOURNAL_NO;
        }

        return  (int) AccountJournal::max('JOURNAL_NO');
    }

    public function getRecord(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select(['JOURNAL_NO'])
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();
      
        if ($data) { // if exists
            return (int) $data->JOURNAL_NO;
        }

        return 0;
    }
    private function getPreviousID(int $ACCOUNT_ID, int $LOCATION_ID): int
    {
        $result = DB::table('account_journal')
            ->select(['ID'])
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }
        return 0;
    }

    private function getEndingLastOutPut(int $ACCOUNT_ID, int $LOCATION_ID, string $OBJECT_DATE)
    {
        $result = DB::table('account_journal')
            ->select(['SEQUENCE_NO', 'ENDING_BALANCE'])
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('OBJECT_DATE', '<=', $OBJECT_DATE)
            ->orderBy('OBJECT_DATE', 'desc')
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return [
                'SEQUENCE_NO' => $result->SEQUENCE_NO,
                'ENDING_BALANCE' => $result->ENDING_BALANCE
            ];
        }

        return [
            'SEQUENCE_NO' => -1,
            'ENDING_BALANCE' => 0
        ];
    }

    public function getSumDebitCredit(int $JOURNAL_NO)
    {
        $result = AccountJournal::query()
            ->select([
                DB::raw('IFNULL(SUM(IF(ENTRY_TYPE=0, AMOUNT, 0)),0) as DEBIT'),
                DB::raw('IFNULL(SUM(IF(ENTRY_TYPE=1, AMOUNT, 0)),0) as CREDIT')
            ])
            ->where('ACCOUNT_JOURNAL.JOURNAL_NO', $JOURNAL_NO)
            ->first();


        if ($result) {

            return [
                'DEBIT' => $result->DEBIT ?? 0,
                'CREDIT' => $result->CREDIT ?? 0
            ];
        }

        return [
            'DEBIT' => 0,
            'CREDIT' => 0
        ];
    }
    private function JournalExists(int $ACCOUNT_ID, int $ENTRY_TYPE, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $LOCATION_ID, int $SUBSIDIARY_ID): bool
    {
        $result = (bool) AccountJournal::query()
            ->where('ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->where('ENTRY_TYPE', '=', $ENTRY_TYPE)
            ->where('OBJECT_ID', '=', $OBJECT_ID)
            ->where('OBJECT_TYPE', '=', $OBJECT_TYPE)
            ->where('OBJECT_DATE', '=', $OBJECT_DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SUBSIDIARY_ID', '=', $SUBSIDIARY_ID)
            ->exists();

        return $result;
    }
    public function AccountSwitch(int $NEW_ACCOUNT_ID, int $OLD_ACCOUNT_ID, int $LOCATION_ID, int $JOURNAL_NO, int $SUBSIDIARY_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE,)
    {

        if ($this->JournalExists(
            $OLD_ACCOUNT_ID,
            $ENTRY_TYPE,
            $OBJECT_ID,
            $OBJECT_TYPE,
            $OBJECT_DATE,
            $LOCATION_ID,
            $SUBSIDIARY_ID,
        )) {
            AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('JOURNAL_NO', $JOURNAL_NO)
                ->where('ACCOUNT_ID', $OLD_ACCOUNT_ID)
                ->where('ENTRY_TYPE', $ENTRY_TYPE)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->update([
                    'ACCOUNT_ID' => $NEW_ACCOUNT_ID
                ]);
        }
    }
    public function JournalModify(int $ACCOUNT_ID, int $LOCATION_ID, int $JOURNAL_NO, int $SUBSIDIARY_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT, int  $SEQUENCE_GROUP, string $EXTENDED_OPTIONS)
    {

        if (!$this->JournalExists($ACCOUNT_ID, $ENTRY_TYPE, $OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $LOCATION_ID, $SUBSIDIARY_ID,)) {

            if ($ACCOUNT_ID  ==  0) {
                return;
            }

            $PREV_ID = (int) $this->getPreviousID($ACCOUNT_ID, $LOCATION_ID);
            $ENDING = $this->getEndingLastOutPut($ACCOUNT_ID, $LOCATION_ID, $OBJECT_DATE);
            $SEQUENCE_NO = (int) $ENDING['SEQUENCE_NO'];
            $ENDING_BALANCE = 0;

            if ($ENTRY_TYPE == 0) {
                $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] + $AMOUNT;
            } else {
                $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] - $AMOUNT;
            }

            $this->Store(
                $PREV_ID,
                $SEQUENCE_NO + 1,
                $JOURNAL_NO,
                $ACCOUNT_ID,
                $LOCATION_ID,
                $SUBSIDIARY_ID,
                $SEQUENCE_GROUP,
                $OBJECT_TYPE,
                $OBJECT_ID,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT,
                $ENDING_BALANCE,
                $EXTENDED_OPTIONS
            );
            return;
        }

        $this->Update(
            $ACCOUNT_ID,
            $LOCATION_ID,
            $SUBSIDIARY_ID,
            $SEQUENCE_GROUP,
            $OBJECT_TYPE,
            $OBJECT_ID,
            $OBJECT_DATE,
            $ENTRY_TYPE,
            $AMOUNT,
            $EXTENDED_OPTIONS
        );
        // no more textended function

    }
    public function JournalExecute(int $JOURNAL_NO, $data, int $LOCATION_ID, int $OBJECT_TYPE, string $OBJECT_DATE, string $EXTENDED = '')
    {
        foreach ($data as $list) {
            $OBJECT_ID = (int) $list->ID;
            $ACCOUNT_ID = (int) $list->ACCOUNT_ID;
            $SUBSIDIARY_ID = (int) $list->SUBSIDIARY_ID;
            $ENTRY_TYPE = (int) $list->ENTRY_TYPE;
            $AMOUNT = (float) $list->AMOUNT;
            $SEQUENCE_GROUP = 0;
            $EXTENDED_OPTIONS = $EXTENDED;

            if (isset($list->SEQUENCE_GROUP)) {
                $SEQUENCE_GROUP = $list->SEQUENCE_GROUP;
            }

            $this->JournalModify(
                $ACCOUNT_ID,
                $LOCATION_ID,
                $JOURNAL_NO,
                $SUBSIDIARY_ID,
                $OBJECT_ID,
                $OBJECT_TYPE,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT,
                $SEQUENCE_GROUP,
                $EXTENDED_OPTIONS
            );
        }
    }


    public function getJournalList(int $JOURNAL_NO): object
    {

        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                'd.DESCRIPTION as TYPE',
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.JOURNAL_NO', $JOURNAL_NO)
            ->get();

        return $result;
    }

    public function getGeneralLedgerList(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = []): object
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                'd.DESCRIPTION as TYPE',
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE',  [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })
            ->orderBy('a.TAG', 'asc')
            ->orderBy('aj.OBJECT_DATE', 'asc')
            ->get();

        return $result;
    }

    public function getTrialBalance(string $dateAs, int $LOCATION_ID, array $account = [], array $accountType = [])
    {
        $result = DB::table('account as a')
            ->select(
                [
                    'a.NAME as ACCOUNT_TITLE',
                    DB::raw("sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)) as AMOUNT "),
                    DB::raw("
                    CASE
                        WHEN t.`ACCOUNT_ORDER` = 0 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 1 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 5 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                    END as TX_DEBIT
                    "),
                    DB::raw("
                    CASE
                        WHEN t.`ACCOUNT_ORDER` = 2 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 3 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 4 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                    END as TX_CREDIT
                    "),
                    't.ACCOUNT_ORDER'
                ]
            )
            ->leftJoin('account_journal as aj', 'aj.ACCOUNT_ID', '=', 'a.ID')
            ->leftJoin('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.OBJECT_DATE', '<=', $dateAs)
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('t.ID', $accountType);
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->groupBy(['a.NAME', 't.ACCOUNT_ORDER'])
            ->orderBy('t.ACCOUNT_ORDER')

            ->get();


        return $result;
    }

    public function getTransactionJournal(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account =  [], array $accountType = [])
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                'd.DESCRIPTION as TYPE',
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })
            ->get();

        return $result;
    }

    public function getUndepositedActiveList(int $LOCATION_ID)
    {
        $undeposited_account_id = 0;
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.OBJECT_DATE as DATE',
                'd.DESCRIPTION as TYPE',
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', '=', $undeposited_account_id)
            ->get();



        return $result;
    }

    public function getAccountTransaction(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = []): object
    {
        $forwardedQuery = DB::table('account_journal as aj')
            ->select([
                DB::raw("'F' as JOURNAL_NO"),
                DB::raw("'2020-1-1' as DATE"),
                DB::raw("'' as ACCOUNT_CODE"),
                DB::raw("a.NAME as ACCOUNT_TITLE"),
                DB::raw("'' as TYPE"),
                DB::raw("'' as LOCATION"),
                DB::raw("'' as TX_NAME"),
                DB::raw("'' as TX_CODE"),
                DB::raw("0 as DEBIT"),
                DB::raw("0 as CREDIT"),
                DB::raw("SUM(if(aj.ENTRY_TYPE = 0,AMOUNT,0)) - SUM(if(aj.ENTRY_TYPE = 1,AMOUNT,0))  as BALANCE")

            ])
            ->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.OBJECT_DATE', '<',  $dateFrom)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })->groupBy(['aj.ACCOUNT_ID', 'a.NAME']);

        $resultQuery = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                'd.DESCRIPTION as TYPE',
                'l.NAME as LOCATION',
                DB::raw($this->TX_NAME),
                DB::raw($this->TX_CODE),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw(" 0  as BALANCE")
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE',  [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            });

        //     $forwardedQuery = DB::table('forwarded_table')
        //     ->select('TAG', 'OBJECT_DATE')
        //     ->where('some_condition', true);

        // $resultQuery = DB::table('result_table')
        //     ->select('TAG', 'OBJECT_DATE')
        //     ->where('another_condition', true);

        $final_result = DB::query()
            ->fromSub(
                $forwardedQuery->union($resultQuery),
                'combined_results'
            )
            ->orderBy('ACCOUNT_TITLE', 'asc')
            ->orderBy('DATE', 'asc')
            ->get();

        return $final_result;
    }
}
