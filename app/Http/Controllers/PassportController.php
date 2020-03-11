<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param TestRequest|Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(TestRequest $request)
    {

//        $validator = Validator::make($request->all(), User::RULES);
//        if ($validator->fails())
//        {
//            return response(['errors'=>$validator->errors()->all()], 422);
//        }

        $request['password']=Hash::make($request['password']);
        $user = User::create($request->toArray());

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];

        return response($response, 200);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                return response([$response,'message'=>'welcome'], 200);
            } else {
                $response = "Password missmatch";
                return response($response, 404);
            }

        } else {
            $response = 'User does not exist';
            return response($response, 404);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
