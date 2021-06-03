# RestaurantTable Controller

Contains APIs for creating, listing, and deleting restaurant tables

## List all Restaurant tables

<small class="badge badge-darkred">requires authentication</small>

This endpoint displays a listing of all restaurant tables. Only "admin" users are allowed to list tables.

> Example request:

```bash
curl -X GET \
    -G "http://laravel-rest.test/api/v1/table" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/table"
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
    "msg": "List of tables",
    "tables": [
        {
            "id": 1,
            "seats": 3,
            "created_at": "2021-06-03T11:57:46.000000Z",
            "updated_at": "2021-06-03T11:57:46.000000Z"
        },
        {
            "id": 2,
            "seats": 4,
            "created_at": "2021-06-03T11:57:58.000000Z",
            "updated_at": "2021-06-03T11:57:58.000000Z"
        }
    ]
}
```
> Example response (404, no tables were found):

```json

{
 'msg': 'No tables were found'
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
<div id="execution-results-GETapi-v1-table" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-v1-table"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-table"></code></pre>
</div>
<div id="execution-error-GETapi-v1-table" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-table"></code></pre>
</div>
<form id="form-GETapi-v1-table" data-method="GET" data-path="api/v1/table" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-table', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-v1-table" onclick="tryItOut('GETapi-v1-table');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-v1-table" onclick="cancelTryOut('GETapi-v1-table');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-v1-table" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/v1/table</code></b>
</p>
<p>
<label id="auth-GETapi-v1-table" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-v1-table" data-component="header"></label>
</p>
</form>


## Add new restaurant table

<small class="badge badge-darkred">requires authentication</small>

This endpoint store a newly created restaurant table. Only "admin" users are allowed to add new tables.

> Example request:

```bash
curl -X POST \
    "http://laravel-rest.test/api/v1/table" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"id":1,"seats":4}'

```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/table"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 1,
    "seats": 4
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
    "msg": "Table created",
    "table": {
        "id": 3,
        "seats": 4,
        "updated_at": "2021-06-03T18:07:59.000000Z",
        "created_at": "2021-06-03T18:07:59.000000Z"
    }
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
> Example response (400, table id is used):

```json

{
 'msg': 'Table id is used'
}
```
> Example response (400):

```json

{
 'msg': 'An error occured!'
}
```
<div id="execution-results-POSTapi-v1-table" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-table"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-table"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-table" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-table"></code></pre>
</div>
<form id="form-POSTapi-v1-table" data-method="POST" data-path="api/v1/table" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-table', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-table" onclick="tryItOut('POSTapi-v1-table');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-table" onclick="cancelTryOut('POSTapi-v1-table');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-table" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/table</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-table" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-table" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-v1-table" data-component="body" required  hidden>
<br>
The id of the table.
</p>
<p>
<b><code>seats</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="seats" data-endpoint="POSTapi-v1-table" data-component="body"  hidden>
<br>
The number of seats offered by the table.
</p>

</form>


## Delete restaurant table

<small class="badge badge-darkred">requires authentication</small>

This endpoint removes the specified restaurant table using a given table id. Only "admin" users are allowed to delete tables.

> Example request:

```bash
curl -X DELETE \
    "http://laravel-rest.test/api/v1/table/ex" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/table/ex"
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
    "msg": "Table deleted"
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
> Example response (400, deleting a table that still has reservations):

```json

{
 'msg': 'Table cannot be deleted due to availability of reservations'
}
```
> Example response (400, table id was not found):

```json

{
 'msg': 'Table was not found'
}
```
> Example response (400):

```json

{
 'msg': 'Table deletion failed'
}
```
<div id="execution-results-DELETEapi-v1-table--table-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-v1-table--table-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-table--table-"></code></pre>
</div>
<div id="execution-error-DELETEapi-v1-table--table-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-table--table-"></code></pre>
</div>
<form id="form-DELETEapi-v1-table--table-" data-method="DELETE" data-path="api/v1/table/{table}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-table--table-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-v1-table--table-" onclick="tryItOut('DELETEapi-v1-table--table-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-v1-table--table-" onclick="cancelTryOut('DELETEapi-v1-table--table-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-v1-table--table-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/v1/table/{table}</code></b>
</p>
<p>
<label id="auth-DELETEapi-v1-table--table-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-v1-table--table-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>table</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="table" data-endpoint="DELETEapi-v1-table--table-" data-component="url" required  hidden>
<br>

</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-v1-table--table-" data-component="url" required  hidden>
<br>
The id of the table.
</p>
</form>



