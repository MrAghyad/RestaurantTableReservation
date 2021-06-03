<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group RestaurantTable Controller
 *
 * Contains APIs for creating, listing, and deleting restaurant tables
 */
class RestaurantTableController extends Controller
{
    /**
	 * Defines authentication middleware that is applied to all endpoints
	 */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * List all Restaurant tables
     *
     * This endpoint displays a listing of all restaurant tables. Only "admin" users are allowed to list tables.
     * @authenticated
     *
     *
     * @response status=200 scenario=success {
     *      "msg": "List of tables",
     *      "tables": [
     *          {
     *              "id": 1,
     *              "seats": 3,
     *              "created_at": "2021-06-03T11:57:46.000000Z",
     *              "updated_at": "2021-06-03T11:57:46.000000Z"
     *          },
     *          {
     *              "id": 2,
     *              "seats": 4,
     *              "created_at": "2021-06-03T11:57:58.000000Z",
     *              "updated_at": "2021-06-03T11:57:58.000000Z"
     *          }
     *      ]
     * }
     *
     * @response status=404 scenario="no tables were found" {
     *  'msg': 'No tables were found'
     * }
     *
     * @response status=401 scenario="user unauthorized" {
     *  'msg': 'Unauthorized user'
     * }
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     */
    public function index()
    {
        //check user is admin, if not return a 401 response
        $checkAuthenticatedAdmin = $this->checkAuthenticatedAdmin();
        if($checkAuthenticatedAdmin != null)
        {
            return $checkAuthenticatedAdmin;
        }

        //get all restaurant tables
        $tables = RestaurantTable::all();

        //if tables were found, return list of tables with status 200
        if($tables->count() > 0)
        {
            $response = [
                'msg' => 'List of tables',
                'tables' => $tables
            ];
            return response()->json($response, 200);
        }

        //otherwise, return message with 404
        $response = [
            'msg' => 'No tables were found'
        ];
        return response()->json($response, 404);
    }


    /**
     * Add new restaurant table
     *
     * This endpoint store a newly created restaurant table. Only "admin" users are allowed to add new tables.
     * @authenticated
     *
     * @bodyParam id integer required The id of the table. Example: 1
     * @bodyParam seats integer The number of seats offered by the table. Example: 4
     *
     * @response status=201 scenario=success {
     *      "msg": "Table created",
     *      "table": {
     *          "id": 3,
     *          "seats": 4,
     *          "updated_at": "2021-06-03T18:07:59.000000Z",
     *          "created_at": "2021-06-03T18:07:59.000000Z"
     *      }
     * }
     *
     *
     * @response status=401 scenario="user unauthorized" {
     *  'msg': 'Unauthorized user'
     * }
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     *
     * @response status=400  scenario="table id is used"{
     *  'msg': 'Table id is used'
     * }
     *
     * @response status=400  {
     *  'msg': 'An error occured!'
     * }
     */
    public function store(Request $request)
    {
        //check user is admin, if not return a 401 response
        $checkAuthenticatedAdmin = $this->checkAuthenticatedAdmin();
        if($checkAuthenticatedAdmin != null)
        {
            return $checkAuthenticatedAdmin;
        }

        //validate request input, and return default error messages in case of mismatching
        $this->validate($request, [
            'id' => 'required|integer',                 //table id is required integer
            'seats' => 'required|integer|between:1,12'  //table seats is required integer between [1-12] seats inclusive
        ]);

        //get input from request
        $table_id = $request->input('id');
        $seats = $request->input('seats');

        //check if table id is already used
        $table = RestaurantTable::find($table_id);
        if($table != null)
        {
            //in case table id was used, return error message
            $response = [
                'msg' => 'Table id is used'
            ];
            return response()->json($response, 400);
        }

        //create new table object using the input data
        $table = new RestaurantTable([
            'id' => $table_id,
            'seats' => $seats
        ]);

        //try to save new table to DB, if succeeded, return status 201 with response containing the created table information and a message
        try{
            if($table->save())
            {
                $response = [
                    'msg' => 'Table created',
                    'table' => $table
                ];

                return response()->json($response, 201);
            }
        }catch(Exception $e)
        {
            //in case of errors that occured while saving table, return error message
            $response = [
                'msg' => 'An error occured!'
            ];

            return response()->json($response, 400);
        }

        //in case table was not saved, return error message
        $response = [
            'msg' => 'An error occured!'
        ];

        return response()->json($response, 400);
    }


    /**
     * Delete restaurant table
     *
     * This endpoint removes the specified restaurant table using a given table id. Only "admin" users are allowed to delete tables.
     * @authenticated
     *
     * @urlParam id integer required The id of the table.
     *
     * @response status=200 scenario=success {
     *      "msg": "Table deleted"
     * }
     *
     * @response status=401 scenario="user unauthorized" {
     *  'msg': 'Unauthorized user'
     * }
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     *
     * @response status=400  scenario="deleting a table that still has reservations"{
     *  'msg': 'Table cannot be deleted due to availability of reservations'
     * }
     *
     * @response status=400  scenario="table id was not found"{
     *  'msg': 'Table was not found'
     * }
     *
     * @response status=400  {
     *  'msg': 'Table deletion failed'
     * }
     */
    public function destroy($id)
    {
        //check user is admin, if not return a 401 response
        $checkAuthenticatedAdmin = $this->checkAuthenticatedAdmin();
        if($checkAuthenticatedAdmin != null)
        {
            return $checkAuthenticatedAdmin;
        }

        //check if the given id of table to be deleted is available, if not return a 400 response with a message
        $table = RestaurantTable::find($id);
        if($table == null)
        {
            $response = [
                'msg' => 'Table was not found'
            ];

            return response()->json($response, 400);
        }

        //check if the table to be deleted has reservations during the current day, if found, return a 400 response with a message stating
        //that tables cannot be deleted if future reservations are available
        $table_reservations = $table->reservations->where('ending_date', '>', now()->format('Y-m-d H:i'));
        if($table_reservations->count() >= 1)
        {
            $response = [
                'msg' => 'Table cannot be deleted due to availability of reservations'
            ];

            return response()->json($response, 400);
        }

        //try to delete table, if deletion failed return a 400 response with a message
        try{
            if(!$table->delete())
            {
                $response = [
                    'msg' => 'Table deletion failed'
                ];

                return response()->json($response, 400);
            }
        }
        catch(Exception $e)
        {
            $response = [
                'msg' => 'Table deletion failed'
            ];

            return response()->json($response, 400);
        }

        //if deletion succeeded respond with a 200 status and a message
        $response = [
            'msg' => 'Table deleted'
        ];

        return response()->json($response, 200);
    }


}
