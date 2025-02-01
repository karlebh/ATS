<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorStoreRequest;
use App\Models\Vendor;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors =
            Vendor::join('items', 'vendors.id', '=', 'items.vendor_id')

            ->select(
                'vendors.*',
                DB::raw('COUNT(items.id) as total_items'),
                DB::raw('SUM(items.amount) as total_amount')
            )
            ->groupBy('vendors.id', 'vendors.name')
            ->get();

        return $this->successResponse('All vendors', ['vendors' => $vendors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VendorStoreRequest $request)
    {
        $code =  rand(00_000, 99_999);
        $data = $request->validated();

        $vendor = Vendor::create(array_merge($data, ['code' => $code]));

        if (!$vendor) {
            return $this->errorResponse('Could not create vendor');
        }

        return $this->successResponse('Vendor created', ['vendor' => $vendor]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return $this->successResponse('Vendor', ['vendor' => $vendor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {

        $data = $request->validated();

        $data = array_filter($data, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $vendor->update($data);

        return $this->successResponse('Vendor data updated successfully', ['vendor' => $vendor]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        return $vendor->delete()
            ? $this->successResponse('Purchase Order deleted successfully')
            : $this->errorResponse('Could not delete vendor');
    }
}
