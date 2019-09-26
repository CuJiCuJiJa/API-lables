<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dd($request);
        $user = null;
        $cant = User::all()->count();

        if ($cant == 0) {                               //IF THE QUERY IS EMPTY

            return response()->json(array(              //RETURN ERROR
                'error' => true,
                'status_code' => 400,
                'response' => 'resource_not_found'));
        }
        if ($request->route()[2]) {                     //IF THE ROUTE HAS A ID PARAMETER

            try
            {
                $id = $request->route()[2]['id'];       //SEARCH FOR THE USER
                $user = User::findOrFail($id);
            }
            catch(ModelNotFoundException $e)            //CATCH THE EXCEMPTION IF THE USER DOES
            {                                           //NOT EXIST
                return response()->json(array(          //RETURN ERROR
                    'error' => true,
                    'status_code' => 400,
                    'response' => 'resource_not_found'));
            }
        }
        return $next($request, $user);
    }
}
