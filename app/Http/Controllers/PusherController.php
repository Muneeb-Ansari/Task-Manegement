<?php

namespace App\Http\Controllers;
use App\Events\PusherBroadcast;

use Illuminate\Http\Request;

class PusherController extends Controller
{
    //
    public function index()
    {

        return view('index');
    }

    public function broadcast(Request $request)
    {
        try {
            //code...
            broadcast(new PusherBroadcast($request->get('message')))->toOthers();
            
            return view('broadcast', ['message' => $request->get('message')]);
        } catch (\Exception $th) {
            //throw $th;
            dd($th);
        }

    }

    public function receive(Request $request)
    {
        return view('receive', ['message' => $request->get('message')]);
    }
}
