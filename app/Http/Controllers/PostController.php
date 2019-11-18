<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Post;

class PostController extends Controller
{
    protected function validator(array $data){
        return Validator::make($data, [
            'name' => 'required|string',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = User::all();
        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);   //VALIDATE INPUT

        if ($validator->passes()) {             //IF VALIDATION PASSES

            $post = new Post;

            $post->name = $data['name'];
            $post->password = Hash::make($data['password']);
            $post->email = $data['email'];

            $post->save();

            return response()->json($post, 201);

        }else{
            return response()->json($validator->errors(), 400); //RETURN VALIDATION ERRORS
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $post = Post::findOrFail($id);

        } catch (ModelNotFoundException $e) {

            return response()->json(array(
                'error' => true,
                'status_code' => 400,
                'response' => 'resource_not_found'));
        }

        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        $data = $request->all();
        $validator = $this->validator($data);                   //VALIDATE INPUT

        if ($validator->passes()) {                             //IF VALIDATION PASSES

            $post->name = $data['name'];
            $post->password = Hash::make($data->password);
            $post->email = $data['email'];

            $post->save();                                      //SAVE DATA
            return response()->json($post, 200);                //RETURN OK
        }else{                                                  //IF NOT

            return response()->json($validator->errors(), 400); //RETURN VALIDATION ERRORS
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();

        return response()->json('Object deleted', 200);
    }
}
