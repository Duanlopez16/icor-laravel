<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * UserController
 */
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

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(\App\Http\Requests\LoginRequest $request)
    {
        try {
            $user = \App\Models\User::whereEmail($request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel')->accessToken;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bienvenido al sistema',
                    'token' => $token,
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
                'message' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        try {

            $user = \Illuminate\Support\Facades\Auth::user();
            $user->tokens->each(function ($token) {
                $token->delete();
            });
            return response()->json([
                'status' => 'success',
                'message' => 'session finalizada.',
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 404);
        }
    }
}
