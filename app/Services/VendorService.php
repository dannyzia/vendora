<?php

namespace App\Services;

use App\Models\Vendor;

class VendorService
{
    /**
     * Get all vendors.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllVendors()
    {
        return Vendor::all();
    }

    /**
     * Get a vendor by ID.
     *
     * @param int $id
     * @return Vendor|null
     */
    public function getVendorById(int $id): ?Vendor
    {
        return Vendor::find($id);
    }
}
