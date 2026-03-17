<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mapel;

class MapelController extends Controller
{
    public function index()
    {
        try {
            return response()->json(['status' => 'success', 'data' => Mapel::all()], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}