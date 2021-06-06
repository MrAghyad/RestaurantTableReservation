<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @group Reservations Controller
 *
 * Contains APIs for creating, listing, and deleting reservations
 */
class ReservationController extends Controller
{
    /**
	 * Defines authentication middleware that is applied to all endpoints
	 */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * List all reservations for today
     *
     * This endpoint displays a paginated listing of all reservations for today. Only authenticated users are allowed to use this endpoint.
     * @authenticated
     *
     * @bodyParam sort string is a string value that can be either 'ASC' or 'DESC' to order results. If not given results are returned in a default order.
     *
     * @response status=200 scenario=success {
     *      "msg": "Reservations of Today",
     *      "reservations": {
     *          "current_page": 1,
     *          "data": [
     *              {
     *                  "id": 3,
     *                  "table_id": "1",
     *                  "starting_date": "2021-06-03 12:00:00",
     *                  "ending_date": "2021-06-03 12:20:00",
     *                  "created_at": "2021-06-03T05:29:01.000000Z",
     *                  "updated_at": "2021-06-03T05:29:01.000000Z"
     *              },
     *              {
     *                  "id": 1,
     *                  "table_id": "1",
     *                  "starting_date": "2021-06-03 12:26:00",
     *                  "ending_date": "2021-06-03 12:45:00",
     *                  "created_at": "2021-06-03T05:28:33.000000Z",
     *                  "updated_at": "2021-06-03T05:28:33.000000Z"
     *              },
     *              {
     *                  "id": 2,
     *                  "table_id": "1",
     *                  "starting_date": "2021-06-03 12:50:00",
     *                  "ending_date": "2021-06-03 12:55:00",
     *                  "created_at": "2021-06-03T05:28:41.000000Z",
     *                  "updated_at": "2021-06-03T05:28:41.000000Z"
     *              }
     *          ],
     *          "first_page_url": "http://127.0.0.1:8000/api/v1/reservation?page=1",
     *          "from": 1,
     *          "last_page": 1,
     *          "last_page_url": "http://127.0.0.1:8000/api/v1/reservation?page=1",
     *          "links": [
     *              {
     *                  "url": null,
     *                  "label": "&laquo; Previous",
     *                  "active": false
     *              },
     *              {
     *                  "url": "http://127.0.0.1:8000/api/v1/reservation?page=1",
     *                  "label": "1",
     *                  "active": true
     *              },
     *              {
     *                  "url": null,
     *                  "label": "Next &raquo;",
     *                  "active": false
     *              }
     *          ],
     *          "next_page_url": null,
     *          "path": "http://127.0.0.1:8000/api/v1/reservation",
     *          "per_page": 5,
     *          "prev_page_url": null,
     *          "to": 3,
     *          "total": 3
     *      }
     * }
     *
     * @response status=404 scenario="no reservations were found" {
     *  'msg': 'No reservations were found'
     * }
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     */
    public function getTodayReservations(Request $request)
    {
        //check user is authenticated. Return negative response 401 otherwise.
        $checkAuthenticate = $this->checkAuthenticate();
        if($checkAuthenticate != null)
        {
            return $checkAuthenticate;
        }

        //Get reservations for today in worktime
        $reservations = Reservation::where('starting_date', '>=', Carbon::today()->setTime(12,0))
                                    ->where('ending_date', '<=', Carbon::today()->setTime(23,59));

        //check if sort is given correctly
        $this->validate($request, [
            'sort' => 'in:ASC,DESC'
        ]);

        //get sort input
        $sort = $request->input('sort');

        //if sort was given, apply ordering on the found reservations for today
        if($sort != null)
        {
            $reservations = $reservations->orderBy('starting_date', $sort);
        }

        //if reservations were found, paginate the found list, and return 200 response with paginated list of reservations for today
        if($reservations->count() > 0)
        {
            $reservations = $reservations->paginate(5);

            $response = [
                'msg' => 'Reservations of Today',
                'reservations' => $reservations
            ];
            return response()->json($response, 200);
        }
        else
        {
            //otherwise, return 404 no reservations found
            $response = [
                'msg' => 'No reservations were found'
            ];
            return response()->json($response, 404);
        }
    }

    /**
     * Add new reservation
     *
     * This endpoint adds a new reservation. Only authenticated users are allowed to use this endpoint.
     * @authenticated
     *
     * @bodyParam table_id integer required is an id of a table.
     * @bodyParam starting_time date_format required is a time value in a string format (H:i) representing the starting time of the reservation. Has to be between (12:00-23:59) and less than ending_time. Example: 13:00
     * @bodyParam ending_time date_format required is a time value in a string format (H:i) representing the ending time of the reservation. Has to be between (12:00-23:59) and more than starting_time. Example: 15:30
     *
     * @response status=201 scenario=success {
     *      "msg": "Reservation created",
     *      "reservation": {
     *          "table_id": "2",
     *          "starting_date": "2021-06-03 12:00",
     *          "ending_date": "2021-06-03 12:20",
     *          "updated_at": "2021-06-03T11:58:52.000000Z",
     *          "created_at": "2021-06-03T11:58:52.000000Z",
     *          "id": 1
     *      }
     * }
     *
     * @response status=400 scenario="starting_time given to be in the past" {
     *  'msg': 'starting_time has to be in the present or future.'
     * }
     *
     * @response status=400 scenario="table already reserved at time" {
     *  'msg': 'Reservation cannot be created. Table is already reserved at the provided time.'
     * }
     *
     * @response status=400  {
     *  'msg': 'An error occured'
     * }
     *
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     */
    public function store(Request $request)
    {
        //check user is authenticated. Return negative response 401 otherwise.
        $checkAuthenticate = $this->checkAuthenticate();
        if($checkAuthenticate != null)
        {
            return $checkAuthenticate;
        }

        //validate request input
        $this->validate($request, [
            'table_id' => 'required',                                                               //table id is required
            'starting_time' => 'required|date_format:H:i|before:ending_time|after_or_equal:12:00',  //starting_time is requried in format of hours:min, has to be between (12:00-end_time)
            'ending_time' => 'required|date_format:H:i|before_or_equal:23:59|after:starting_time',  //ending_time is requried in format of hours:min, has to be between (starting_time-23:59)
        ]);

        //get input
        $table_id = $request->input('table_id');
        $starting_time = $request->input('starting_time');
        $ending_time = $request->input('ending_time');

        //check if reservation's start time happens to be in the past of today
        if($starting_time < Carbon::now()->format('H:i'))
        {
            //return a 400 bad request response with a message
            $response = [
                'msg' => 'starting_time has to be in the present or future.'
            ];

            return response()->json($response, 400);
        }

        //get full date of today and given time
        $dateTodayStart = Carbon::today()->setTimeFromTimeString($starting_time)->format('Y-m-d H:i');
        $dateTodayEnd = Carbon::today()->setTimeFromTimeString($ending_time)->format('Y-m-d H:i');

        //check for conflicts with the given time today
        $conflictingReservations = DB::table('reservations')
                                    ->where('table_id', '=', $table_id)
                                    ->where(function ($query) use ($dateTodayStart,$dateTodayEnd) {
                                        $query->where(function($query) use ($dateTodayStart, $dateTodayEnd) {
                                                $query->where('starting_date','>=',$dateTodayStart)
                                                ->where('starting_date','<',$dateTodayEnd);
                                        })
                                            ->orWhere(function($query) use ($dateTodayStart, $dateTodayEnd) {
                                                $query->where('ending_date','>',$dateTodayStart)
                                                      ->where('ending_date','<=',$dateTodayEnd);
                                            });
                                        })->get();

        //check if conflicts found
        if($conflictingReservations->count() >= 1)
        {
            // return 400 status code with message, as table is already reserved in the given time
            $response = [
                'msg' => 'Reservation cannot be created. Table is already reserved at the provided time.'
            ];

            return response()->json($response, 400);
        }

        //create reservation object
        $reservation = new Reservation([
            'table_id' => $table_id,
            'starting_date' => $dateTodayStart,
            'ending_date' => $dateTodayEnd
        ]);

        //try saving the created reservation
        try{
            //if succeeded, return 201 with the created reservation and a message
            if($reservation->save())
            {
                $response = [
                    'msg' => 'Reservation created',
                    'reservation' => $reservation
                ];

                return response()->json($response, 201);
            }
        }//otherwise, report an error
        catch(Exception $e)
        {
            $response = [
                'msg' => 'An error has occured'
            ];

            return response()->json($response, 400);
        }

        $response = [
            'msg' => 'An error has occured'
        ];

        return response()->json($response, 400);

    }



    /**
     * Delete reservation
     *
     * This endpoint removes the specified reservation using a given id. Only authenticated users are allowed to use this endpoint.
     * @authenticated
     *
     * @urlParam id integer required The id of the reservation.
     *
     * @response status=200 scenario=success {
     *      "msg": "Reservation canceled"
     * }
     *
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     *
     * @response status=404  scenario="Reservation not found"{
     *  'msg': 'Reservation not found!'
     * }
     *
     * @response status=400  scenario="cancelling old reservations"{
     *  'msg': 'Old reservation cannot be cancelled'
     * }
     *
     * @response status=400  scenario='Reservation cancellation failed'{
     *  'msg': 'Reservation cancellation failed'
     * }
     *
     */
    public function destroy($id)
    {
        //check authentication, return 401 response if not valid
        $checkAuthenticate = $this->checkAuthenticate();
        if($checkAuthenticate != null)
        {
            return $checkAuthenticate;
        }

        //try to find reservation of the given id
        try{
            $reservation = Reservation::findOrFail($id);
        }catch(Exception $e)
        {
            //if not found return message with 404 status code
            $response = [
                'msg' => 'Reservation not found!'
            ];

            return response()->json($response, 404);
        }

        //check if reservation is old
        $rsrv_end_date = date('Y-m-d H:i', strtotime($reservation->ending_date));
        $date_today = Carbon::now()->format('Y-m-d H:i');
        if($rsrv_end_date < $date_today)
        {
            //if old, it cannot be deleted, return message with 400 status code
            $response = [
                'msg' => 'Old reservation cannot be cancelled'
            ];

            return response()->json($response, 400);
        }

        //try to cancel reservation, in case of DB error, return error message
        try{
            if(!$reservation->delete())
            {
                $response = [
                    'msg' => 'Reservation cancellation failed'
                ];

                return response()->json($response, 400);
            }
        }
        catch(Exception $e)
        {
            $response = [
                'msg' => 'Reservation cancellation failed'
            ];

            return response()->json($response, 400);
        }

        //reservation canclled, and 200 code is returned
        $response = [
            'msg' => 'Reservation canceled'
        ];

        return response()->json($response, 200);
    }


    /**
     * List all reservations
     *
     * This endpoint displays a paginated listing of all reservations. Only "admin" users are allowed to use this endpoint.
     * @authenticated
     *
     * @bodyParam tables_ids integer[] is a list table ids for filtering.
     * @bodyParam starting_date date_format is a date value in a string format (Y-m-d H:i) representing the starting date point for filtering. Example: 2021-06-02 13:00
     * @bodyParam ending_date date_format  is a date value in a string format (Y-m-d H:i) representing the ending  date point for filtering. Example: 2021-06-03 15:00
     *
     * @response status=200 scenario=success {
     *      "msg": "All found reservations",
     *      "reservations": {
     *          "current_page": 1,
     *          "data": [
     *              {
     *                  "id": 1,
     *                  "table_id": "2",
     *                  "starting_date": "2021-06-03 12:00:00",
     *                  "ending_date": "2021-06-03 12:20:00",
     *                  "created_at": "2021-06-03 11:58:52",
     *                  "updated_at": "2021-06-03 11:58:52"
     *              }
     *          ],
     *          "first_page_url": "http://127.0.0.1:8000/api/v1/reservation/all?page=1",
     *          "from": 1,
     *          "last_page": 1,
     *          "last_page_url": "http://127.0.0.1:8000/api/v1/reservation/all?page=1",
     *          "links": [
     *              {
     *                  "url": null,
     *                  "label": "&laquo; Previous",
     *                  "active": false
     *              },
     *              {
     *                  "url": "http://127.0.0.1:8000/api/v1/reservation/all?page=1",
     *                  "label": "1",
     *                  "active": true
     *              },
     *              {
     *                  "url": null,
     *                  "label": "Next &raquo;",
     *                  "active": false
     *              }
     *          ],
     *          "next_page_url": null,
     *          "path": "http://127.0.0.1:8000/api/v1/reservation/all",
     *          "per_page": 5,
     *          "prev_page_url": null,
     *          "to": 1,
     *          "total": 1
     *      }
     * }
     *
     * @response status=404 scenario="no reservations were found" {
     *  'msg': 'No reservations were found'
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
    public function getAll(Request $request)
    {
        //check authenticated user is admin
        $checkAuthenticatedAdmin = $this->checkAuthenticatedAdmin();
        if($checkAuthenticatedAdmin != null)
        {
            return $checkAuthenticatedAdmin;
        }
        //validate input format
        $this->validate($request, [
            'tables_ids' => 'array',
            'starting_date' => 'date_format:Y-m-d H:i',
            'ending_date' => 'date_format:Y-m-d H:i',
        ]);

        //get input from request
        $tables_ids = $request->input('tables_ids');
        $starting_date = $request->input('starting_date');
        $ending_date = $request->input('ending_date');

        //get reservations
        $reservations = DB::table('reservations');

        //if tables_ids is given, filter the results based on the given ids
        if($tables_ids != null)
        {
            $reservations = $reservations->whereIn('table_id', $tables_ids);
        }

        //if starting_date is given, filter the results based on it where starting_date of reservations is greater than or equal the given one (lower bound)
        if($starting_date != null)
        {
            $reservations = $reservations->where('starting_date', '>=', date($starting_date,strtotime('Y-m-d H:i')));
        }

        //if ending_date is given, filter the results based on it where ending_date of reservations is less than or equal the given one (upper bound)
        if($ending_date != null)
        {
            $reservations = $reservations->where('ending_date', '<=', date($ending_date,strtotime('Y-m-d H:i')));
        }

        //in case no reservations found, show not found message
        if($reservations->count() == 0)
        {
            $response = [
                'msg' => 'No reservations were found'
            ];

            return response()->json($response, 404);
        }
        else
        {
            //in case reservations found, show found reservations in paginated manner
            $reservations = $reservations->paginate(5);
            $response = [
                'msg' => 'All found reservations',
                'reservations' => $reservations
            ];
        }

        return response()->json($response, 200);
    }


    /**
     * check available time slots for today
     *
     * This endpoint checks available time slots for today using a given number of seats required. Only authenticated users are allowed to use this endpoint.
     * @authenticated
     *
     * @urlParam seats integer required The number of seats for a reservation.
     *
     * @response status=200 scenario=success {
     *      "msg": "Available time slots today",
     *      "tables": {
     *          "2": [
     *              {
     *                  "seats": 4,
     *                  "from": "20:07",
     *                  "to": "23:59"
     *              }
     *          ]
     *      }
     * }
     *
     * @response status=401 scenario="user not authenticated" {
     *  'msg': 'Not authenticated'
     * }
     *
     * @response status=400  scenario="no tables are defined in the restuarant"{
     *  'msg': 'No tables are defined in the restuarant'
     * }
     *
     * @response status=400  scenario="the number of seats provided is invalidt"{
     *  'msg': 'The number of seats provided is invalid'
     * }
     *
     * @response status=404  scenario='No time solts are available today'{
     *  'msg': ''No time solts are available today'
     * }
     *
     */
    public function checkAvailable($seats)
    {
        //check authenticated user
        $checkAuthenticate = $this->checkAuthenticate();
        if($checkAuthenticate != null)
        {
            return $checkAuthenticate;
        }

        //get the maximum number of seats in the restaurant's tables
        $max_seats = RestaurantTable::max('seats');

        //if the maximum number of seats is null, then no tables were created. Hence, reponde with an error message
        if($max_seats == null)
        {
            $response = [
                'msg' => 'No tables are defined in the restuarant'
            ];
            return response()->json($response, 400);
        }

        //validate the given seats, it has to be integer and within range of (1:max(restaurant_seats))
        $validator = Validator::make(['seats' => $seats], [
            'seats' => 'required|integer|between:1,'. $max_seats
        ]);

        //if input validation failed, return an error message indicating that the input is mismatched
        if ($validator->fails())
        {
            $response = [
                'msg' => 'The number of seats provided is invalid, it has to be between (1,' .$max_seats . ')'
            ];
            return response()->json($response, 400);
        }

        //create date object of today's workinghours starting point
        $start_date_today = Carbon::today()->setTime(12,0);

        //if the current time is within workinghours, set the starting time of the search to be now
        if(Carbon::now()->format('H:i') > Carbon::today()->setTime(12,0)->format('H:i'))
        {
            $start_date_today = Carbon::now()->format('Y-m-d H:i');
        }

        //create a date object of ending time for today
        $end_date_today = Carbon::today()->setTime(23,59);

        //get list of tables where seats are >= the given seats and order in ascending way
        $tables = RestaurantTable::where('seats', '>=', $seats)->orderBy('seats','ASC')->get();

        //get a joint table of reservations and tables within remaining time range today and with valid seats, and group them by table id
        $reservations = DB::table('reservations')
                        ->join('restaurant_tables','reservations.table_id','=','restaurant_tables.id')
                        ->select('reservations.id','reservations.starting_date', 'reservations.ending_date','reservations.table_id','restaurant_tables.seats')
                        ->where('restaurant_tables.seats', '>=', $seats)
                        ->where('starting_date', '>=', $start_date_today)
                        ->where('ending_date', '<=', $end_date_today)
                        ->orderBy('restaurant_tables.seats','ASC')
                        ->orderBy('reservations.starting_date','ASC')
                        ->get()->groupBy(function ($val) {
                            return $val->table_id;
                        });


        //definition of available times array, to be filled later
        $available_times = [];

        //get time only (H:i) of start and end dates today
        $start_time_today = date('H:i', strtotime($start_date_today));
        $end_time_today = date('H:i', strtotime($end_date_today));

        //foreach table in restaurant's valid tables with given seats
        foreach ($tables as $table) {
            //if table is not in the joint table (i.e. which is grouped by table id)
            if(! $reservations->has($table->id))
            {
                //then this table has no reservations,
                //add a key of table id to available_times, if not available in it
                if(!Arr::exists($available_times, $table->id)){
                    $available_times[$table->id] = [];
                }

                //add this table with details of time slots to available_times as a collection, from 12:00 to 23:59
                array_push($available_times[$table->id],
                    collect([
                    'seats'=> $table->seats,
                    'from' => $start_time_today,
                    'to'=> $end_time_today])
                );

                continue;
            }
            //otherwise, if table was found in the joint table of reservations
            $table_reservations = $reservations[$table->id];

            //get earlist reservation in this table
            $rsrv_starting_time = date('H:i',strtotime($table_reservations[0]->starting_date));

            //if earlist reservation's starting_time is later than start_time of today, then there is a time slot between these times
            if($rsrv_starting_time > $start_time_today)
            {
                //if table id is not in available_times, add it
                if(!Arr::exists($available_times, $table->id)){
                    $available_times[$table->id] = [];
                }

                //add this table with details of time slots to available_times as a collection, from start_time of today to starting_time of earlist reservation
                array_push($available_times[$table->id],
                    collect([
                    'seats'=> $table->seats,
                    'from' => $start_time_today,
                    'to'=> $rsrv_starting_time])
                );
            }

            //for reservations in table
            for($i = 0; $i + 1 <= $table_reservations->count() - 1; $i++)
            {
                //get times of consecutive reservations
                //get the ending time of first reservation
                $rsrv_ending_time_0 = date('H:i',strtotime($table_reservations[$i]->ending_date));
                //get the starting time of second reservation
                $rsrv_starting_time_1 = date('H:i',strtotime($table_reservations[$i + 1]->starting_date));

                //if both times are not equal, then there is a time slot between both reservations
                if($rsrv_ending_time_0 != $rsrv_starting_time_1)
                {
                    //if table id is not in available_times, add it
                    if(!Arr::exists($available_times, $table->id)){
                        $available_times[$table->id] = [];
                    }

                    //add this table with details of time slots to available_times as a collection, from ending time of first reservation to starting time of second reservation
                    array_push($available_times[$table->id],
                        collect([
                        'seats'=> $table->seats,
                        'from' => $rsrv_ending_time_0,
                        'to'=> $rsrv_starting_time_1])
                    );
                }
            }

            //get latest reservation in this table
            $last_rsrv_ending_time = date('H:i',strtotime($table_reservations[$table_reservations->count() - 1]->ending_date));

            //if latest reservation's ending_time is ealier than end_time of today, then there is a time slot between these times
            if($last_rsrv_ending_time < $end_time_today)
            {
                if(!Arr::exists($available_times, $table->id)){
                    $available_times[$table->id] = [];
                }
                //add this table with details of time slots to available_times as a collection, from ending_time of latest reservation to ending_time of today
                array_push($available_times[$table->id],
                    collect([
                    'seats'=> $table->seats,
                    'from' => $last_rsrv_ending_time,
                    'to'=> $end_time_today])
                );
            }

        }

        //if no times found, return message of no time slots found
        if(count($available_times) ==  0)
        {
            $response = [
                'msg' => 'No time solts are available today'
            ];

            return response()->json($response, 404);
        }
        //otherwise, return slots grouped by tables and ordered by time and seats ascending
        $response = [
            'msg' => 'Available time slots today',
            'tables' => $available_times
        ];

        return response()->json($response, 200);
    }
}
