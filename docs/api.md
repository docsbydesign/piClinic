---
title: "piClinic - API"
permalink: /api/
---
# piClinic API

The [piClinic Console](https://piclinic.org) exposes the piClinic API for automated testing and application development.

These piClinic API resources are available to developers:

* _**[icd](#icd-resource)**_ - search supported ICD-10 diagnostic codes
* _**[session](#session-resource)**_ - open and close sessions to access the piClinic API resources

# piClinic API usage

piClinic API users must be authenticated by a username and password and some resources require user accounts with specific authorizations.

## Request format

GET method requests pass parameters as query parameters and POST method requests can pass data as `application/json` data or as query parameters; however, passing as `application/json` data can prevent the data from appearing in server logs. API requests that require authorization, must also include an **X-piClinic-token** header with a valid session token. Session tokens are obtained by calling the **session** API.

### Token header example

```
X-piClinic-token: c1cbed0e_082f_4c85_afaa_3e4286b840fd
```

### GET request example with query parameters

```
https://piclinic_host/api/icd.php?c=R51&language=en
```

where _**piclinic_host**_ is the host address.

## Success response format

Generally, API requests return a JSON response that consists of these objects:

| -------- | -------- |
| Object | Contents |
| -------- | -------- |
| **data** | The requested data, if any is returned |
| **count** | The number of elements returned in **data**. This is `0` when no data is returned. |
| **status** | The status code and additional information about the response  |
| **debug** | Detailed information about the request for debugging (development systems only) |

### Success response example

```
{
    "count": 1,
    "data": [
        {
            "language": "en",
            "icd10code": "R51",
            "icd10index": "R51",
            "shortDescription": "Headache",
            "useCount": "1",
            "lastUsedDate": "2019-06-12 18:24:13"
        }
    ],
    "status": {
        "httpResponse": 200,
        "httpReason": "Success-1"
    }
}
```

## Error response format

Error responses usually contain:

| -------- | -------- |
| Object | Contents |
| -------- | -------- |
| **data** | Empty or null |
| **count** | `0` |
| **status** | A non "200" status code and an explanation of the error. |
| **debug** | Detailed information about the request for debugging (development systems only) |

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
# piClinic API resource references

These piClinic API resources are available to developers:

* _**[icd](#icd-resource)**_ - search supported ICD-10 diagnostic codes
* _**[session](#session-resource)**_ - open and close sessions to access the piClinic API resources

## _icd_ resource

The **icd** resource contains ICD-10 (CIE-10) diagnostic codes in English and Spanish.

The ICD-10 is the International Classification of Diseases (10th Revision) or, in Spanish, (CIE-10) La Clasificación internacional de enfermedades (Décima edición), which describes and provides a code for thousands of diseases and is managed by the World Health Organization. The piClinic Console supports the ICD-10, 2008-version, to the first decimal place, which provides about 12,000 codes in English and Spanish.

Note, some diagnostic codes are supported in only one language.

| ------ | ------ |
| Method | Token required? | Action |
| ------ | ------ |
| GET | Yes | Look up a code and its description |


### GET method

Returns the selected ICD-10 code object or objects.

#### GET request parameters

A GET request must include one, and only one, of **q**, **t**, or **c**.

| ------ | ------ | ------ | ------ |
| Parameter | Value | Function | Example |
| ------ | ------ | ------ | ------ |
| **q** | free text | Return codes with description text or code value that contains the parameter value | `q=Headache` |
| **t** | free text | Return codes with description text that contains the parameter value | `t=Headache` |
| **c** | ICD-10 code | Return codes with a code value that matches the parameter value | `c=R51` |

A GET request can also include any or all of these parameters to organize the response data.

| ------ | ------ | ------ | ------ |
| Parameter | Values | Function | Example |
| ------ | ------ | ------ | ------ |
| **language** | `en` or `es` | filter results to return only the specified language | `language=en` |
| **sort** | `c`, `t`, or `d` | sort the returned data by ascending **c**_ode_, **t**_ext_, or _last-used_ **d**_ate_)

#### GET request example

```
https://piclinic_host/api/icd.php?c=R51&language=en
```

where _**piclinic_host**_ is the host address.

#### GET response example

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
#### GET response data properties

| ------ | ------ | ------ |
| Properties | Data type | Description |
| ------ | ------ | ------ |
| **icd10code** | string | ICD-10 code with decimal formatting |
| **icd10index** | string | ICD-10 code with no decimal formatting |
| **language** | enum:<br>`en`, `es` | Language code of the response data |
| **lastUsedDate** | datetime | Date & time the diagnosis code was assigned to a patient record |
| **shortDescription** | string | Description of disease that corresponds to the `icd10code` |
| **useCount** | number | The number of times the diagnosis has been assigned to a patient record since the system was installed |

## _session_ resource

The **session** resource contains the valid user sessions that can access the piClinic API.

| ------ | ------ | ------ |
| Method | Token required? | Action |
| ------ | ------ | ------ |
| GET | Yes | Look up a token to get its validity and access authorizations |
| POST | No | Create a new session |
| PATCH | Yes | Update the settings of a current session |
| DELETE | Yes | Close a session and invalidate its token |

The normal sequence of session interaction is to:

1. POST a session to create a new session and receive its token
2. GET a session using the token to test its access (this usually done only by administrators and is not commonly used by an application)
3. PATCH a session to change its default settings
4. DELETE a session to close it and invalidate its token so it cannot by used again

### GET method

Returns information about the selected user session.

#### GET request parameters

The **X-piClinic-token** header value is used to identify the session owner and, for most users, the query parameters are ignored. System administrators, however, can request information about another token by passing the desired token as a query parameter.

| ------ | ------ | ------ | ------ |
| Parameter | Values | Function | Example |
| ------ | ------ | ------ | ------ |
| **token** | a valid session token | tests token for validity and returns access information (SystemAdmin access only) | token=c1cbed0e_082f_4c85_afaa_3e4286b840fd |

#### GET request example
```
https://piclinic_host/session.php
```

where _**piclinic_host**_ is the host address.

#### GET response example
```
{
    "count": 1,
    "data": {
        "token": "dd710b65_2434_4326_927a_823596170770",
        "sessionIP": "66.115.183.147",
        "sessionUA": "PostmanRuntime/7.13.0",
        "username": "SystemAdmin",
        "loggedIn": 1,
        "accessGranted": "SystemAdmin",
        "sessionLanguage": "en",
        "sessionClinicPublicID": null,
        "createdDate": "2019-06-13 13:32:31",
        "expiresOnDate": "2019-06-14 13:32:31"
    },
    "status": {
        "httpResponse": 201,
        "httpReason": "New session created."
    }
}
```

#### GET response properties

| ------ | ------ | ------ |
| Properties | Data type | Description |
| ------ | ------ | ------ |
| **accessGranted** |  enum:<br>`SystemAdmin, `ClinicAdmin`, `ClinicStaff`, `ClinicReadOnly`  | Access authorization level |
| **loggedIn** | Boolean | `1` = active session, `0` = expired or closed session |
| **sessionIP** | IP address | The IP of the user account |
| **sessionUA** | Text | The User Agent of the user's browser when the session was created |
| **token** | Token GUID | The token created when the session was created |
| **sessionLanguage** | enum:<br>`en`, `es` | Language code of the response data |
| **username** | Text | The user's username |
| **sessionClinicPublicID** | Text | Reserved. |
| **createdDate** | datetime | The date and time the session was created. |
| **expiresOnDate** | datetime | The date and time the session will expire automatically. |

### POST method

Validates the credentials passed and creates a new user session if they are valid.

#### POST request parameters

The **X-piClinic-token** header value is not required for POST requests

| ------ | ------ | ------ | ------ |
| Parameter | Values | Function | Example |
| ------ | ------ | ------ | ------ |
| **username** | a valid username (not case sensitive) | identify the user for whom to create a new session | (see example below) |
| **password** | the password for the user (case sensitive) | authenticate the user creating a new session | (see example below) |

#### POST request example
```
https://piclinic_host/session.php
```

where _**piclinic_host**_ is the host address.

Note, a **X-piClinic-token** header is not required for a POST request.

#### POST data object example

```
{
	"username": "TestUser",
	"password": "MyPassword"
}
```

#### POST response example

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
#### POST response properties

| ------ | ------ | ------ |
| Properties | Data type | Description |
| ------ | ------ | ------ |
| **accessGranted** |  enum:<br>`SystemAdmin`<br>`ClinicAdmin`<br>`ClinicStaff`<br>`AuthenticatedUser`  | Access authorization level |
| **loggedIn** | Boolean | `1` = active session, `0` = expired or closed session |
| **sessionIP** | IP address | The IP of the user account |
| **sessionUA** | Text | The User Agent of the user's browser when the session was created |
| **token** | Token GUID | The token created when the session was created |
| **sessionLanguage** | enum:<br>`en`, `es` | Language code of the response data |
| **username** | Text | The user's username |
| **sessionClinicPublicID** | Text | Reserved. |
| **createdDate** | datetime | The date and time the session was created. |
| **expiresOnDate** | datetime | The date and time the session will expire automatically. |


### PATCH method

Updates selected properties of a session.

#### PATCH request parameters

| ------ | ------ | ------ | ------ |
| Parameter | Values | Function | Example |
| ------ | ------ | ------ | ------ |
| **sessionLanguage** | `en` or `es` | Sets the session's default language | `sessionLanguage=es` |

The **sessionLanguage** property is the only session property that can be modified.

#### PATCH request example
```
https://piclinic_host/session.php&sessionLanguage=es
```

where _**piclinic_host**_ is the host address.


#### PATCH response example

```
{
    "count": 1,
    "data": {
        "token": "8c7300aa_bb77_4dd3_9126_93eb72645876",
        "username": "TestSA",
        "accessGranted": "SystemAdmin",
        "sessionLanguage": "es",
        "sessionClinicPublicID": null
    },
    "status": {
        "httpResponse": 200,
        "httpReason": "Success"
    }
}
```

#### PATCH response properties

| ------ | ------ | ------ |
| Properties | Data type | Description |
| ------ | ------ | ------ |
| **accessGranted** |  enum:<br>`SystemAdmin`<br>`ClinicAdmin`<br>`ClinicStaff`<br>`AuthenticatedUser`  | Access authorization level |
| **token** | Token GUID | The token created when the session was created |
| **sessionLanguage** | enum:<br>`en`, `es` | Language code of the response data |
| **username** | Text | The user's username |
| **sessionClinicPublicID** | Text | Reserved. |

### DELETE method

Closes the specified session.

#### DELETE request parameters

The DELETE request requires only a **X-piClinic-token** header.

#### DELETE request example
```
https://piclinic_host/session.php
```

#### DELETE response example

```
{
    "count": 0,
    "data": "",
    "status": {
        "httpResponse": 200,
        "httpReason": "User session deleted."
    }
}
```

#### DELETE response properties

The DELETE method returns no data, only status information.
