---
title: "piClinic - API"
permalink: /api/
---

The piClinic exposes an API for automated testing and application development.

# piClinic API resources

These resources are available to developers:

* **[icd](#icd)** - search suported ICD-10 diagnostic codes
* **[session](#session)** - open and close sessions to access the piClinic resources

# piClinic API usage

piClinic API users must be authenticated by a username and password and some resources require specific authorization.

## Request format

GET method requests pass parameters as query parameters and POST method requests can pass data as `application/json` data or as query parameters. API requests that require authorization, must also include an **X-piClinic-token** header with a valid session token returned by the session API.

### Sample header

```
X-piClinic-token: c1cbed0e_082f_4c85_afaa_3e4286b840fd
```

### Sample GET request with query parameters

```
https://piclinic_host/api/icd.php?c=R51&language=en
```

where _**piclinic_host**_ is the host address.

## Success response 

Generally, successful API requests return a JSON response that consists of these objects:

| -------- | -------- |
| Object | Contents |
| -------- | -------- | 
| **data** | The requested data |
| **count** | The number of elements returned in **data** |
| **status** | The status of the response  |
| **debug** | Additional information about the request (development systems only) |

### Success response example

```
{
    "count": 2,
    "data": [
        {
            "language": "en",
            "icd10code": "R51",
            "icd10index": "R51",
            "shortDescription": "Headache",
            "useCount": "1",
            "lastUsedDate": "2019-06-12 18:24:13"
        },
        {
            "language": "es",
            "icd10code": "R51",
            "icd10index": "R51",
            "shortDescription": "Cefalea",
            "useCount": "0",
            "lastUsedDate": null
        }
    ],
    "status": {
        "httpResponse": 200,
        "httpReason": "Success-n"
    }
}
```

## Error response

Error responses usually contain:

| -------- | -------- |
| Object | Contents |
| -------- | -------- | 
| **data** | Empty or null |
| **count** | `0` |
| **status** | Explanation of the error and the HTTP error code |
| **debug** | Additional information about the request (development systems only) |

### Error response example 

```
{
    "count": 0,
    "data": "",
    "status": {
        "httpResponse": 404,
        "httpReason": "Resource not found. No Records."
    }
}
```

## icd

The **icd** resource contains ICD-10 (CIE-10) diagnostic codes in English and Spanish.

### Supported methods

| ------ | ------ |
| Method | Action |
| ------ | ------ |
| GET | Look up a code and its description |

This resource required a valid session token to access.

### Supported GET request parameters

A GET request must include one, and only one, of **q**, **t**, or **c**.

| ------ | ------ | ------ | ------ |
| Parameter | Supported values | Function | Example |
| ------ | ------ | ------ | ------ |
| **q** | free text | Return codes with description text or code value that matches the parameter value | `q=Headache` |
| **t** | free text | Return codes with description text that matches the parameter value | `t=Headache` |
| **c** | ICD-10 code | Return codes with a code value that matches the parameter value | `c=R51` |
| **language** | `en` or `es` | filter results to return only the specified language | `language=en` |
| **sort** | `c`, `t`, or `d` | sort the returned data by ascending **c**_ode_, **t**_ext_, or _last-used_ **d**_ate_)

#### GET request example

```
https://piclinic_host/api/icd.php?c=R51&language=en
```

where _**piclinic_host**_ is the host address.

```
{
    "count": 1,
    "data": {
        "language": "en",
        "icd10code": "R51",
        "icd10index": "R51",
        "shortDescription": "Headache",
        "useCount": "1",
        "lastUsedDate": "2019-06-12 18:24:13"
    },
    "status": {
        "httpResponse": 200,
        "httpReason": "Success-1"
    }
}
```

## session

The **session** resource contains the valid user sessions that can access the piClinic API.

The normal sequence of session interaction is to:

1. POST a session to create a new session and receive its token
2. GET a session using the token to test its access (this usually done by the API and is not commonly used by an application)
3. PATCH a session to change its default settings
4. DELETE a session to close it and invalidate its token so it cannot by used again


### Supported methods

| ------ | ------ | ------ |
| Method | Token required? | Action |
| ------ | ------ | ------ |
| GET | Yes | Look up a token to get its validity and access authorizations |
| POST | No | Create a new session |
| PATCH | Yes | Update the settings of a current sesion |
| DELETE | Yes | Close a session and invalidate its token |

### Supported GET request parameters

For administrator-level access, the **X-piClinic-token** header value is used to identify the session and the query parameter is ignored. System administrators can request information about another valid token by passing it as a query parameter.

| ------ | ------ | ------ | ------ |
| Parameter | Supported values | Function | Example |
| ------ | ------ | ------ | ------ |
| **token** | a valid session token | tests token for validity and returns access information (System admin access only) | token=c1cbed0e_082f_4c85_afaa_3e4286b840fd | 

### Supported POST request parameters

The **X-piClinic-token** header value is not required for POST requests

| ------ | ------ | ------ | ------ |
| Parameter | Supported values | Function | Example |
| ------ | ------ | ------ | ------ |
| **username** | a valid username (not case sensitive) | identify the user for whom to create a new session | (see example below) |
| **password** | the password for the user (case sensitive) | authenticate the user creating a new session | (see example below) |

#### Sample POST data object 

```
{
	"username": "TestUser",
	"password": "MyPassword"
}
```

#### Sample POST data response

```
{
    "count": 1,
    "data": {
        "token": "8c7300aa_bb77_4dd3_9126_93eb72645876",
        "sessionIP": "66.115.183.147",
        "sessionUA": "PostmanRuntime/7.13.0",
        "username": "TestUser",
        "loggedIn": 1,
        "accessGranted": "SystemAdmin",
        "sessionLanguage": "en",
        "sessionClinicPublicID": null,
        "createdDate": "2019-06-13 13:17:17",
        "expiresOnDate": "2019-06-14 13:17:17"
    },
    "status": {
        "httpResponse": 201,
        "httpReason": "New session created."
    }
}
```


### Supported PATCH request parameters

| ------ | ------ | ------ | ------ |
| Parameter | Supported values | Function | Example |
| ------ | ------ | ------ | ------ |

### Supported DELETE request parameters

| ------ | ------ | ------ | ------ |
| Parameter | Supported values | Function | Example |
| ------ | ------ | ------ | ------ |