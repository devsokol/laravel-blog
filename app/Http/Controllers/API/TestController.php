<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class TestController extends Controller
{
    /**
     *
     */
    public function test1() {
        return response()->json([
            'success' => true,
            'data' => User::all()
        ]);
    }
}
