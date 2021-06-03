# User Controller

Contains APIs for adding users and logging in

## Add a user

<small class="badge badge-darkred">requires authentication</small>

This endpoint lets you add a user. Only admins are allowed to add users.

> Example request:

```bash
curl -X POST \
    "http://laravel-rest.test/api/v1/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"id":"1234","name":"Ahmed","role":"admin","password":"123456"}'

```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": "1234",
    "name": "Ahmed",
    "role": "admin",
    "password": "123456"
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
    "msg": "User created",
    "user": {
        "id": 2232,
        "name": "Ali",
        "role": "employee",
        "updated_at": "2021-06-03T17:17:15.000000Z",
        "created_at": "2021-06-03T17:17:15.000000Z"
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
> Example response (400, user id is used):

```json

{
 'msg': 'User id is used'
}
```
> Example response (400):

```json

{
 'msg': 'An error occured! User was not created'
}
```
<div id="execution-results-POSTapi-v1-user" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-user"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-user"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-user"></code></pre>
</div>
<form id="form-POSTapi-v1-user" data-method="POST" data-path="api/v1/user" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-user', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-user" onclick="tryItOut('POSTapi-v1-user');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-user" onclick="cancelTryOut('POSTapi-v1-user');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-user" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/user</code></b>
</p>
<p>
<label id="auth-POSTapi-v1-user" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-v1-user" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="POSTapi-v1-user" data-component="body" required  hidden>
<br>
The id of the user is a string of 4 digits.
</p>
<p>
<b><code>name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="name" data-endpoint="POSTapi-v1-user" data-component="body" required  hidden>
<br>
The user name of alpha characters.
</p>
<p>
<b><code>role</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="role" data-endpoint="POSTapi-v1-user" data-component="body" required  hidden>
<br>
The role of the user can be either admin, or employee.
</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-v1-user" data-component="body" required  hidden>
<br>
The user password consists of at least 6 characters.
</p>

</form>


## Login a user


This endpoint allows a user to login.

> Example request:

```bash
curl -X POST \
    "http://laravel-rest.test/api/v1/user/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"id":"dolorem","password":"beatae"}'

```

```javascript
const url = new URL(
    "http://laravel-rest.test/api/v1/user/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": "dolorem",
    "password": "beatae"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, success):

```json
{
    "msg": "User loggedin",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvdXNlclwvbG9naW4iLCJpYXQiOjE2MjI3NDA2MTMsImV4cCI6MTYyMjc0NDIxMywibmJmIjoxNjIyNzQwNjEzLCJqdGkiOiJ5aWttb0NlMmt0MmQ0TW9CIiwic3ViIjoxMjM1LCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.1_njtfBJGRgTx08awCYxtS6fLlIZPC8uABsB5n97lzw"
}
```
> Example response (401, invalid credentials):

```json

{
 'msg': 'Invalid credentials'
}
```
> Example response (500):

```json

{
 'msg': 'Could not create token'
}
```
<div id="execution-results-POSTapi-v1-user-login" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-v1-user-login"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-user-login"></code></pre>
</div>
<div id="execution-error-POSTapi-v1-user-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-user-login"></code></pre>
</div>
<form id="form-POSTapi-v1-user-login" data-method="POST" data-path="api/v1/user/login" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-user-login', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-v1-user-login" onclick="tryItOut('POSTapi-v1-user-login');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-v1-user-login" onclick="cancelTryOut('POSTapi-v1-user-login');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-v1-user-login" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/v1/user/login</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="POSTapi-v1-user-login" data-component="body" required  hidden>
<br>

</p>
<p>
<b><code>password</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="password" name="password" data-endpoint="POSTapi-v1-user-login" data-component="body" required  hidden>
<br>

</p>

</form>



