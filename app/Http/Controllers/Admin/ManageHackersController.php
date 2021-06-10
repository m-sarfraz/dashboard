<?php

namespace App\Http\Controllers\Admin;

use App\Hacker;
use APP\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SetDecisionRequest;

class ManageHackersController extends Controller
{
    public function index()
    {
        $hackers = Hacker::with('team')->get();
        // $hackers = User::all();

        $data = [
            'hackers' => $hackers,
        ];

        return view('dashboard.hackers', $data);
    }

    public function update(SetDecisionRequest $request, Hacker $hacker)
    {
        $hacker->decision = $request->decision;
        $hacker->save();

        return response(200);
    }
}
