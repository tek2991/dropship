<?php

namespace App\Imports;

use App\Models\Invoice;
use App\Models\LogSheet;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeleteImportedData implements ToCollection, WithHeadingRow
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Declare log_sheet_ids and invoice_ids arrays
        $log_sheet_ids = [];
        $invoice_ids = [];

        // Loop through the collection and get the log_sheet_id and invoice_id
        foreach ($collection as $row) {
            // Get the log_sheet_id
            $log_sheet_id = LogSheet::where('log_sheet_no', $row['log_sheet'])->first()->id;
            // Get the invoice_id
            $invoice_id = Invoice::where('invoice_no', $row['invoice_no'])->first()->id;
            // Add the log_sheet_id to the log_sheet_ids array
            array_push($log_sheet_ids, $log_sheet_id);
            // Add the invoice_id to the invoice_ids array
            array_push($invoice_ids, $invoice_id);
        }

        
        // Delete duplicates from the log_sheet_ids array
        $log_sheet_ids = array_unique($log_sheet_ids);
        
        // Delete duplicates from the invoice_ids array
        $invoice_ids = array_unique($invoice_ids);

        // Permanently Delete invoices
        Invoice::destroy($invoice_ids);

        // Delete log_sheets
        LogSheet::destroy($log_sheet_ids);

    }
}
