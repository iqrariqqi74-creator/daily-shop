<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonController extends Controller
{

    public function index(Request $request)
    {
        $options = $request->options;
        $id = $request->get('tenant_id');

        if (!empty($options)) {
            $options = explode(',', $options);
        }

        if (!empty($options)) {
            foreach ($options as $option) {
                $interface_name = ucfirst($option) . 'Interface';
                // dd($interface_name);

                if (file_exists(app_path() . '/Interfaces/Backend/' . $interface_name . '.php')) {

                    $repository = 'App\Repositories\Backend\\' . ucfirst($option) . 'Repository';
                    $repository = new $repository;
                    // dd($repository);
                    $return_index = $option . 's';

                    if ($option == 'company') {
                        $return_index = 'companies';
                    }

                    if ($option == 'category') {
                        $return_index = 'categories';
                    }
                    if ($option == 'tax') {
                        $return_index = 'taxes';
                    }

                    $response = $repository->getOptions($request, $id);

                    if (!empty($response)) {
                        $response_data = $response->getData();

                        $data = !empty($response_data->$return_index) ? $response_data->$return_index : [];
                    }

                    $options[$return_index] = $data;
                }
            }
        }
        return json_encode($options);
    }
}