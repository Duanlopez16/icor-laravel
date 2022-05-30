<?php

namespace App\Http\Controllers;

/**
 * CountryController
 */
class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $countries = \App\Models\Country::where('status', '=', '1')
                ->orderBy('name')
                ->get(['id', 'uuid', 'name']);

            if (!empty($countries->toArray())) {
                $response_data = \App\Services\Utils::get_response('success', 'success', $countries);
            } else {
                $response_data = \App\Services\Utils::get_response('succcess', 'No encontramos países.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * get_countries_pag
     *
     * @return \Illuminate\Http\Response
     */
    public function get_countries_pag()
    {
        $limit = request()->query('limit') ?? 10;
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $countries = \App\Models\Country::select('id', 'uuid', 'name')
                ->OrderBy('name', 'desc')->paginate($limit);
            $response_data = \App\Services\Utils::get_response('success', 'success', $countries);
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }
        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * get_country
     *
     * @param  int $country_id
     * @return void
     */
    public function get_country(int $country_id)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $country_data = \App\Models\Country::where('id', '=', $country_id)->first(['id', 'uuid', 'name']);
            if (!empty($country_data)) {
                $country_data->cities->where('status', '=', 1);
                $response_data = \App\Services\Utils::get_response('success', 'success', $country_data);
            } else {
                $response_data = \App\Services\Utils::get_response('success', 'No encontramos el país ingresado.');
            }
        } catch (\Exception $ex) {
            $response_data = \App\Services\Utils::get_response('error', $ex->getMessage());
        }

        return response()->json($response_data->response_data, $response_data->status_code);
    }

    /**
     * get_country_uuid
     *
     * @param  int $uuid
     * @return void
     */
    public function get_country_uuid($uuid)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $response_validate = \App\Services\Utils::validate_uuid($uuid);
            if ($response_validate->status) {
                $country_data = \App\Models\Country::where('uuid', '=', $uuid)->where('status', '=', 1)->first(['id', 'uuid', 'name']);
                if (!empty($country_data)) {
                    $country_data->cities->where('status', '=', 1);
                    $response_data = \App\Services\Utils::get_response('succcess', 'success', $country_data);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\CountryRequest $request)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $model = new \App\Models\Country();
            $model->name = $request->name;
            if ($model->save()) {
                $response_data = \App\Services\Utils::get_response('succcess', 'add', $model->uuid);
            } else {
                $response_data = \App\Services\Utils::get_response('error', 'Error al guardar el registro');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\CountryRequest $request, string $uuid)
    {
        $response_data = \App\Services\Utils::get_response('error', 'error');
        try {
            $response_validate =  \App\Services\Utils::validate_uuid($uuid);
            if ($response_validate->status) {
                $country_data = \App\Models\Country::where('uuid', '=', $uuid)->where('status', '=', 1)->first();
                if (!empty($country_data)) {
                    $country_data->name = $request->name;
                    if ($country_data->update()) {
                        $response_data = \App\Services\Utils::get_response('error', 'Actualizado correctamente.', $country_data->uuid);
                    } else {
                        $response_data = \App\Services\Utils::get_response('error', 'No se logro editar el registro.');
                    }
                } else {
                    $response_data = \App\Services\Utils::get_response('error', 'No encontramos el país ingresado.');
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
                $country_data = \App\Models\Country::where('uuid', '=', $uuid)->where('status', '=', 1)->first();
                if (!empty($country_data)) {
                    $country_data->status = 0;
                    if ($country_data->update()) {
                        $response_data = \App\Services\Utils::get_response('success', 'Eliminado correctamente.');
                    } else {
                        $response_data = \App\Services\Utils::get_response('error', 'No se logró eliminar el país.');
                    }
                } else {
                    $response_data = \App\Services\Utils::get_response('error', 'No encontramos el país seleccionado.');
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
