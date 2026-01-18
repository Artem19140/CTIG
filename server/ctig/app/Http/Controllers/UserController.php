<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller{

    public function show(){
        $users = User::all();
        return response()->json(
            [
                "result"=>"ok",
                "users"=>$users
            ]
        );
    }

    public function store(Request $request):JsonResponse{
        $user = User::where("email", $request->input('email'))->first();
        if($user){
            throw new Exception("Пользак уже есть, пока");
        }
        User::create(request()->all());
        return response()->json(["result" => "ok"]);
    }
}