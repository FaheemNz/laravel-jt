<?php

namespace App\Http\Controllers;

use App\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index()
    {
        $disputes = Dispute::latest()->get();
        return view("disputes.index", compact("disputes"));
    }

    public function create(Request $request)
    {

    }
}
