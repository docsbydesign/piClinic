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

GET method requests pass parameters as query parameters and POST method requests can pass data as URL-form-encoded data or as query parameters. API requests that require authorization, must also include an **X-piClinic-token** header with a valid session token returned by the session API.

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

The **icd** resource provides a list of ICD-10 (CIE-10) diagnostic codes in English and Spanish.

### Supported methods

| ------ | ------ |
| Method | Action |
| ------ | ------ |
| GET | Look up a code and its description || PATCH | Update the last-used date of a specific code |

This resource required a valid session token to access.

### Supported GET query parameters

A GET request must include one, and only one, of **q**, **t**, or **c**.

| ------ | ------ | ------ | ------ |
| Parameter | Supported values | Function | Example |
| ------ | ------ | ------ | ------ |
| **q** | free text | Return codes with description text or code value that matches the parameter value | `q=Headache` |
| **t** | free text | Return codes with description text that matches the parameter value | `t=Headache` |
| **c** | ICD-10 code | Return codes with a code value that matches the parameter value | `c=R51` |
| **language** | `en` or `es` | filter results to return only the specified language | `language=en` |
| **sort** | `c`, `t`, or `d` | sort the returned data by ascending **c**_ode_, **t**_ext_, or _last-used_ **d**_ate_)

### GET query example

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