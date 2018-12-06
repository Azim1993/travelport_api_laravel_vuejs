<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Airport;

class AirportController extends Controller
{
    public function search(Request $request)
    {
        $airports = Airport::query();
        if ($request->has('origin'))
            $airports = $airports->where('name', 'LIKE', '%' . $request->origin . '%')
                            ->orWhere('code', 'LIKE', '%' . strtoupper ($request->origin) . '%');
        $airports = $airports->take(30)->get()->toArray();
        $airports = array_map(function($airport) {
            return [
                'code' => $airport['code'],
                'name' => $airport['name'].','.$airport['cityName'].','.$airport['countryName']
            ];
        }, $airports);
        return response()->json($airports);
    }
}
