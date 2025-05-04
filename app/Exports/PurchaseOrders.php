<?php

namespace App\Exports;

use App\Models\PurchaseOrder as PurchaseOrderModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseOrders implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PurchaseOrderModel::all();
    }
}
