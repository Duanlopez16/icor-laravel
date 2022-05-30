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
                ->get(['id', 'uuid', 'name', 'country_id']);

            if (!empty($cities->toArray())) {

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
     * get_cities_pagination
     *
     * @return void
     */
    public function get_cities_pagination()
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');

        try {
            $limit = request()->query('limit') ?? 10;
            $cities = \App\Models\City::select('id', 'uuid', 'name')
                ->OrderBy('name', 'desc')->paginate($limit);
            $response_data = \App\Services\Utils::get_response('success', 'success', $cities);
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }

        return response()->json($response_data->response_data, $response_data->status_code);
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
            $model->country_id = $request->country_id;
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
    public function show(int $id)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $city_data = \App\Models\City::where('id', '=', $id)->first(['id', 'uuid', 'name', 'country_id']);
            if (!empty($city_data)) {
                $country = $city_data->country()->first('name');
                $city_data->country_name = $country->name  ?? '';
                $response_data = \App\Services\Utils::get_response('success', 'success', $city_data);
            } else {
                $response_data = \App\Services\Utils::get_response('success', 'No encontramos la ciudad ingresado.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * get_cities_country
     *
     * @param  mixed $country_id
     * @return \Illuminate\Http\Response
     */
    public function get_cities_country(int $country_id)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $cities_country = \App\Models\City::where('status', '=', 1)->where('country_id', '=', $country_id)->get(['id', 'uuid', 'name']);
            if (!empty($cities_country->toArray())) {
                $response_data = \App\Services\Utils::get_response('success', 'success', $cities_country);
            } else {
                $response_data = \App\Services\Utils::get_response('success', 'No encontramos ciudades en el país ingresado.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * get_city_uuid
     *
     * @param  mixed $uuid
     * @return void
     */
    public function get_city_uuid($uuid)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $response_validate = \App\Services\Utils::validate_uuid($uuid);
            if ($response_validate->status) {
                $city_data = \App\Models\City::where('uuid', '=', $uuid)->where('status', '=', 1)->first(['id', 'uuid', 'name', 'country_id']);
                if (!empty($city_data)) {
                    $country = $city_data->country()->first('name');
                    $city_data->country_name = $country->name  ?? '';
                    $response_data = \App\Services\Utils::get_response('succcess', 'success', $city_data);
                } else {
                    $response_data = \App\Services\Utils::get_response('succcess', 'No encontramos el país ingresado.');
                }
            } else {
                $response_data = \App\Services\Utils::get_response('error', $response_validate->message);
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\CityRequest $request, string $uuid)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $response_validate =  \App\Services\Utils::validate_uuid($uuid);
            if ($response_validate->status) {
                $city_data = \App\Models\City::where('uuid', '=', $uuid)->where('status', '=', 1)->first();
                if (!empty($city_data)) {
                    $city_data->name = $request->name;
                    $city_data->country_id = $request->country_id;
                    if ($city_data->update()) {
                        $response_data = \App\Services\Utils::get_response('error', 'Actualizado correctamente.', $city_data->uuid);
                    } else {
                        $response_data = \App\Services\Utils::get_response('error', 'No se logro editar el registro.');
                    }
                } else {
                    $response_data = \App\Services\Utils::get_response('error', 'No encontramos el ciudad ingresado.');
                }
            } else {
                $response_data = \App\Services\Utils::get_response('error', $response_validate->message);
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        try {
            $validate_uuid =  \App\Services\Utils::validate_uuid($uuid);
            if ($validate_uuid->status) {
                $city_data = \App\Models\City::where('uuid', '=', $uuid)->where('status', '=', 1)->first();
                if (!empty($city_data)) {
                    $city_data->status = 0;
                    if ($city_data->update()) {
                        $response_data = \App\Services\Utils::get_response('success', 'Eliminado correctamente.');
                    } else {
                        $response_data = \App\Services\Utils::get_response('error', 'No se logró eliminar el país.');
                    }
                } else {
                    $response_data = \App\Services\Utils::get_response('error', 'No encontramos el ciudad seleccionado.');
                }
            } else {
                $response_data = \App\Services\Utils::get_response('error', $validate_uuid->message);
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }
}
