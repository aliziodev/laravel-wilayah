<?php

namespace Aliziodev\Wilayah\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Aliziodev\Wilayah\Facades\Wilayah;

class WilayahController extends Controller
{
    /**
     * Get list of provinces.
     */
    public function provinces(Request $request)
    {
        // format: [{value: "11", label: "ACEH"}, ...]
        return response()->json(
            Wilayah::forSelect('provinces')
        );
    }

    /**
     * Get list of regencies by province code.
     */
    public function regencies(Request $request)
    {
        $provinceCode = $request->query('province');

        if (! $provinceCode) {
            return response()->json(['message' => 'Query parameter "province" is required.'], 400);
        }

        return response()->json(
            Wilayah::forSelect('regencies', province: $provinceCode)
        );
    }

    /**
     * Get list of districts by regency code.
     */
    public function districts(Request $request)
    {
        $regencyCode = $request->query('regency');

        if (! $regencyCode) {
            return response()->json(['message' => 'Query parameter "regency" is required.'], 400);
        }

        return response()->json(
            Wilayah::forSelect('districts', regency: $regencyCode)
        );
    }

    /**
     * Get list of villages by district code.
     */
    public function villages(Request $request)
    {
        $districtCode = $request->query('district');

        if (! $districtCode) {
            return response()->json(['message' => 'Query parameter "district" is required.'], 400);
        }

        return response()->json(
            Wilayah::forSelect('villages', district: $districtCode)
        );
    }
}
