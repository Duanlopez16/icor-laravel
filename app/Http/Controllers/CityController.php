<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

/**
 * CityController
 */
class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $cities = City::where('status', '=', '1')
                ->orderBy('name')
                ->get();

            if (!empty($cities->items)) {

                return response()->json([
                    'status' => 'succcess',
                    'message' => 'succcess',
                    'data' => $cities,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'succcess',
                    'message' => 'No encontramos ciudades.',
                    'data' => [],
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\CityRequest $request)
    {
        try {
            $model = new \App\Models\City;
            $model->name = $request->name;
            if ($model->save()) {
                return response()->json([
                    'message' => 'Registro exitoso',
                    'data' => $model
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Error al guardar el registro',
                    'data' => false
                ], 400);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage(),
                'data' => []
            ], 400);
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
}
