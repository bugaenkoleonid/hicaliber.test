<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    public function search(Request $request)
    {
        $query = House::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->has('bathrooms')) {
            $query->where('bathrooms', $request->bathrooms);
        }

        if ($request->has('storeys')) {
            $query->where('storeys', $request->storeys);
        }

        if ($request->has('garages')) {
            $query->where('garages', $request->garages);
        }

        if ($request->has('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }

        if ($request->has('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        return response()->json($query->get());
    }
} 