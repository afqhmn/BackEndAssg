<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Collection;
use Excel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\FromCollection;
//use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->get('data');

        if($request->has('search')){
            $user = User::select('name','email','id','position')->where('name','like',"%$request->search%")->orWhere('email','like', "%$request->search%")->orWhere('position','like', "%$request->search%")->get();
        }else{
            $user = User::paginate(5);
        }

        return response()->json([
            'data'=>$user]);
    }

    public function show(User $user)
        //public function show($id)
    {

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User with id ' . $user . ' not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $user->toArray()
        ], 400);
    }

    public function store(TestRequest $request, User $user) //same like register so store function actually no need in here
    {


//        $request['password']=Hash::make($request['password']);
//        $user = User::create($request->toArray());

//
//        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
//        $response = ['token' => $token];
//
//        return response($response, 200);
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->position = $request->input('position');

        $user->save();
        return response()->json($user);

       //$validated = $request->validated();

        if (auth()->user()->user()->save($user))
            return response()->json([
                'success' => true,
                'data' => $user->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'User could not be added'
            ], 500);
    }

    public function update(Request $request, User $user)
    {
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User with id ' . $user->id . ' not found'
            ], 404);
        }
        $update = $user->update($request->all());

        if ($update) {
            return response()->json(['message' => 'updated']);

        } else {
            return response()->json(['message' => 'Cannot be updated']);
            //dd('Cannot be updated');
        }

    }

    public function destroy(Request $request, User $user)
    {
        //$user = auth()->user()->user()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User with id ' . $user->id . ' not found'
            ], 404);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User could not be deleted'
            ], 500);
        }
    }

}


