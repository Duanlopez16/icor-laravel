<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {
        try {
            $rules = array(
                'email' => 'required|email',
                'password' => 'required',
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $validator->errors();
            }
            $user = User::whereEmail($request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel')->accessToken;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bienvenido al sistema',
                    'token' => $token->token,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email o password incorrecto',
                ], 400);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 400);
        }
    }
}
