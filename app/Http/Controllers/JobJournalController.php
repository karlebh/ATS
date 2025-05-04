<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobJournalResource;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class JobJournalController extends Controller
{
    public function index()
    {
        // $purchaseOrder = PurchaseOrder::with('parts')->paginate(9);
        $purchaseOrder = PurchaseOrder::with('parts')->latest()->get();
        return JobJournalResource::collection($purchaseOrder);
    }
}
