# Reservations Controller

Contains APIs for creating, listing, and deleting reservations

## List all reservations for today

<small class="badge badge-darkred">requires authentication</small>

This endpoint displays a paginated listing of all reservations for today. Only authenticated users are allowed to use this endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://laravel-rest.test/api/v1/reservation" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"sort":"odio"}'

```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/reservation"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "sort": "odio"
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "msg": "Reservations of Today",
    "reservations": {
        "current_page": 1,
        "data": [
            {
                "id": 3,
                "table_id": "1",
                "starting_date": "2021-06-03 12:00:00",
                "ending_date": "2021-06-03 12:20:00",
                "created_at": "2021-06-03T05:29:01.000000Z",
                "updated_at": "2021-06-03T05:29:01.000000Z"
            },
            {
                "id": 1,
                "table_id": "1",
                "starting_date": "2021-06-03 12:26:00",
                "ending_date": "2021-06-03 12:45:00",
                "created_at": "2021-06-03T05:28:33.000000Z",
                "updated_at": "2021-06-03T05:28:33.000000Z"
            },
            {
                "id": 2,
                "table_id": "1",
                "starting_date": "2021-06-03 12:50:00",
                "ending_date": "2021-06-03 12:55:00",
                "created_at": "2021-06-03T05:28:41.000000Z",
                "updated_at": "2021-06-03T05:28:41.000000Z"
            }
        ],
        "first_page_url": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation",
        "per_page": 5,
        "prev_page_url": null,
        "to": 3,
        "total": 3
    }
}
```
> Example response (404, no reservations were found):

```json

{
 'msg': 'No reservations were found'
}
```
> Example response (401, user not authenticated):

```json

{
 'msg': 'Not authenticated'
}
```
<div id="execution-results-GETapi-v1-reservation" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-reservation"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reservation"></code></pre>
</div>
<div id="execution-error-GETapi-v1-reservation" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reservation"></code></pre>
</div>
<form id="form-GETapi-v1-reservation" data-method="GET" data-path="api/v1/reservation" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reservation', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-reservation" onclick="tryItOut('GETapi-v1-reservation');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-reservation" onclick="cancelTryOut('GETapi-v1-reservation');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-reservation" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/reservation</code></b>
</p>
<p>
<label id="auth-GETapi-v1-reservation" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-reservation" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>sort</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="sort" data-endpoint="GETapi-v1-reservation" data-component="body"  hidden>
<br>
is a string value that can be either 'ASC' or 'DESC' to order results. If not given results are returned in a default order.
</p>

</form>


## Add new reservation

<small class="badge badge-darkred">requires authentication</small>

This endpoint adds a new reservation. Only authenticated users are allowed to use this endpoint.

> Example request:

```bash
curl -X POST \
    "http://laravel-rest.test/api/v1/reservation" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"table_id":12,"starting_time":"13:00","ending_time":"15:30"}'

```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/reservation"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "table_id": 12,
    "starting_time": "13:00",
    "ending_time": "15:30"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (201, success):

```json
{
    "msg": "Reservation created",
    "reservation": {
        "table_id": "2",
        "starting_date": "2021-06-03 12:00",
        "ending_date": "2021-06-03 12:20",
        "updated_at": "2021-06-03T11:58:52.000000Z",
        "created_at": "2021-06-03T11:58:52.000000Z",
        "id": 1
    }
}
```
> Example response (400, starting_time given to be in the past):

```json

{
 'msg': 'starting_time has to be in the present or future.'
}
```
> Example response (400, table already reserved at time):

```json

{
 'msg': 'Reservation cannot be created. Table is already reserved at the provided time.'
}
```
> Example response (400):

```json

{
 'msg': 'An error occured'
}
```
> Example response (401, user not authenticated):

```json

{
 'msg': 'Not authenticated'
}
```
<div id="execution-results-POSTapi-v1-reservation" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-reservation"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-reservation"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-reservation" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-reservation"></code></pre>
</div>
<form id="form-POSTapi-v1-reservation" data-method="POST" data-path="api/v1/reservation" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-reservation', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-reservation" onclick="tryItOut('POSTapi-v1-reservation');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-reservation" onclick="cancelTryOut('POSTapi-v1-reservation');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-reservation" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/reservation</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-reservation" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-reservation" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>table_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="table_id" data-endpoint="POSTapi-v1-reservation" data-component="body" required  hidden>
<br>
is an id of a table.
</p>
<p>
<b><code>starting_time</code></b>&nbsp;&nbsp;<small>date_format</small>  &nbsp;
<input type="text" name="starting_time" data-endpoint="POSTapi-v1-reservation" data-component="body" required  hidden>
<br>
is a time value in a string format (H:i) representing the starting time of the reservation. Has to be between (12:00-23:59) and less than ending_time.
</p>
<p>
<b><code>ending_time</code></b>&nbsp;&nbsp;<small>date_format</small>  &nbsp;
<input type="text" name="ending_time" data-endpoint="POSTapi-v1-reservation" data-component="body" required  hidden>
<br>
is a time value in a string format (H:i) representing the ending time of the reservation. Has to be between (12:00-23:59) and more than starting_time.
</p>

</form>


## Delete reservation

<small class="badge badge-darkred">requires authentication</small>

This endpoint removes the specified reservation using a given id. Only authenticated users are allowed to use this endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://laravel-rest.test/api/v1/reservation/modi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/reservation/modi"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "msg": "Reservation canceled"
}
```
> Example response (401, user not authenticated):

```json

{
 'msg': 'Not authenticated'
}
```
> Example response (404, Reservation not found):

```json

{
 'msg': 'Reservation not found!'
}
```
> Example response (400, cancelling old reservations):

```json

{
 'msg': 'Old reservation cannot be cancelled'
}
```
> Example response (400, Reservation cancellation failed):

```json

{
 'msg': 'Reservation cancellation failed'
}
```
<div id="execution-results-DELETEapi-v1-reservation--reservation-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-reservation--reservation-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-reservation--reservation-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-reservation--reservation-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-reservation--reservation-"></code></pre>
</div>
<form id="form-DELETEapi-v1-reservation--reservation-" data-method="DELETE" data-path="api/v1/reservation/{reservation}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-reservation--reservation-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-reservation--reservation-" onclick="tryItOut('DELETEapi-v1-reservation--reservation-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-reservation--reservation-" onclick="cancelTryOut('DELETEapi-v1-reservation--reservation-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-reservation--reservation-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/reservation/{reservation}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-reservation--reservation-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-reservation--reservation-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>reservation</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="reservation" data-endpoint="DELETEapi-v1-reservation--reservation-" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-v1-reservation--reservation-" data-component="url" required  hidden>
<br>
The id of the reservation.
</p>
</form>


## List all reservations

<small class="badge badge-darkred">requires authentication</small>

This endpoint displays a paginated listing of all reservations. Only "admin" users are allowed to use this endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://laravel-rest.test/api/v1/reservation/all" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"tables_ids":[9,7],"starting_date":"2021-06-02 13:00","ending_date":"2021-06-03 15:00"}'

```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/reservation/all"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "tables_ids": [
        9,
        7
    ],
    "starting_date": "2021-06-02 13:00",
    "ending_date": "2021-06-03 15:00"
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "msg": "All found reservations",
    "reservations": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "table_id": "2",
                "starting_date": "2021-06-03 12:00:00",
                "ending_date": "2021-06-03 12:20:00",
                "created_at": "2021-06-03 11:58:52",
                "updated_at": "2021-06-03 11:58:52"
            }
        ],
        "first_page_url": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation\/all?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation\/all?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation\/all?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http:\/\/127.0.0.1:8000\/api\/v1\/reservation\/all",
        "per_page": 5,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```
> Example response (404, no reservations were found):

```json

{
 'msg': 'No reservations were found'
}
```
> Example response (401, user unauthorized):

```json

{
 'msg': 'Unauthorized user'
}
```
> Example response (401, user not authenticated):

```json

{
 'msg': 'Not authenticated'
}
```
<div id="execution-results-GETapi-v1-reservation-all" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-reservation-all"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reservation-all"></code></pre>
</div>
<div id="execution-error-GETapi-v1-reservation-all" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reservation-all"></code></pre>
</div>
<form id="form-GETapi-v1-reservation-all" data-method="GET" data-path="api/v1/reservation/all" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reservation-all', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-reservation-all" onclick="tryItOut('GETapi-v1-reservation-all');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-reservation-all" onclick="cancelTryOut('GETapi-v1-reservation-all');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-reservation-all" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/reservation/all</code></b>
</p>
<p>
<label id="auth-GETapi-v1-reservation-all" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-reservation-all" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>tables_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="tables_ids.0" data-endpoint="GETapi-v1-reservation-all" data-component="body"  hidden>
<input type="number" name="tables_ids.1" data-endpoint="GETapi-v1-reservation-all" data-component="body" hidden>
<br>
is a list table ids for filtering.
</p>
<p>
<b><code>starting_date</code></b>&nbsp;&nbsp;<small>date_format</small>     <i>optional</i> &nbsp;
<input type="text" name="starting_date" data-endpoint="GETapi-v1-reservation-all" data-component="body"  hidden>
<br>
is a date value in a string format (Y-m-d H:i) representing the starting date point for filtering.
</p>
<p>
<b><code>ending_date</code></b>&nbsp;&nbsp;<small>date_format</small>     <i>optional</i> &nbsp;
<input type="text" name="ending_date" data-endpoint="GETapi-v1-reservation-all" data-component="body"  hidden>
<br>
is a date value in a string format (Y-m-d H:i) representing the ending  date point for filtering.
</p>

</form>


## check available time slots for today

<small class="badge badge-darkred">requires authentication</small>

This endpoint checks available time slots for today using a given number of seats required. Only authenticated users are allowed to use this endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://laravel-rest.test/api/v1/reservation/available/7" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/reservation/available/7"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "msg": "Available time slots today",
    "tables": {
        "2": [
            {
                "seats": 4,
                "from": "20:07",
                "to": "23:59"
            }
        ]
    }
}
```
> Example response (401, user not authenticated):

```json

{
 'msg': 'Not authenticated'
}
```
> Example response (400, no tables are defined in the restuarant):

```json

{
 'msg': 'No tables are defined in the restuarant'
}
```
> Example response (400, the number of seats provided is invalidt):

```json

{
 'msg': 'The number of seats provided is invalid'
}
```
> Example response (404, No time solts are available today):

```json

{
 'msg': ''No time solts are available today'
}
```
<div id="execution-results-GETapi-v1-reservation-available--seats-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-reservation-available--seats-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-reservation-available--seats-"></code></pre>
</div>
<div id="execution-error-GETapi-v1-reservation-available--seats-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-reservation-available--seats-"></code></pre>
</div>
<form id="form-GETapi-v1-reservation-available--seats-" data-method="GET" data-path="api/v1/reservation/available/{seats}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-reservation-available--seats-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-reservation-available--seats-" onclick="tryItOut('GETapi-v1-reservation-available--seats-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-reservation-available--seats-" onclick="cancelTryOut('GETapi-v1-reservation-available--seats-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-reservation-available--seats-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/reservation/available/{seats}</code></b>
</p>
<p>
<label id="auth-GETapi-v1-reservation-available--seats-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-reservation-available--seats-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>seats</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="seats" data-endpoint="GETapi-v1-reservation-available--seats-" data-component="url" required  hidden>
<br>
The number of seats for a reservation.
</p>
</form>



