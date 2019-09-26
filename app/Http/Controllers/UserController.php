<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('userExist', ['only' => [
    //         'show',
    //         'update',
    //         'destroy',
    //         'index']]);
    // }

    protected function validator(array $data){
        return Validator::make($data, [
            'name' => 'required|string',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users'
        ]);
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);   //VALIDATE INPUT

        if ($validator->passes()) {             //IF VALIDATION PASSES

            $user = new User;

            $user->name = $data['name'];
            $user->password = Hash::make($data['password']);
            $user->email = $data['email'];

            $user->save();

            return response()->json($user, 201);
        }else{
            return response()->json($validator->errors(), 400); //RETURN VALIDATION ERRORS
        }
    }

    public function show($id)
    {
        try {

            $user = User::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            return response()->json(array(
                'error' => true,
                'status_code' => 400,
                'response' => 'resource_not_found'));
        }

        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $data = $request->all();
        $validator = $this->validator($data);                   //VALIDATE INPUT

        if ($validator->passes()) {                             //IF VALIDATION PASSES

            $user->name = $data['name'];
            $user->password = Hash::make($data->password);
            $user->email = $data['email'];

            $user->save();                                      //SAVE DATA
            return response()->json($user, 200);                //RETURN OK
        }else{                                                  //IF NOT

            return response()->json($validator->errors(), 400); //RETURN VALIDATION ERRORS
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json('Object deleted', 200);
    }
}
