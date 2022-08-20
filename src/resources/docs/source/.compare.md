---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Authentication


Registeration and Login
<!-- START_2be1f0e022faf424f18f30275e61416e -->
## Login

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"ut","password":"ut"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ut",
    "password": "ut"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 13,
            "role_id": null,
            "name": "",
            "first_name": "John",
            "last_name": "Doe",
            "email": "JohnDoe@gmail.com",
            "avatar": "users\/default.png",
            "phone_no": "1122334455",
            "rating": 0,
            "currency_id": 1,
            "image_id": null,
            "settings": null,
            "email_verified_at": null,
            "is_disabled": 0,
            "admin_review": null,
            "device_token": null,
            "created_at": "2022-03-05T14:05:39.000000Z",
            "updated_at": "2022-03-05T14:05:39.000000Z",
            "deleted_at": null,
            "currency": null,
            "image": []
        },
        "token": "Bearer eyJ0eXAi...",
        "expires_at": "2023-03-05 14:08:05",
        "customer_rating": 0,
        "traveler_rating": 0
    },
    "message": "You are Logged in successfully"
}
```
> Example response (422):

```json
{
    "success": false,
    "message": [
        "The email field is required.",
        "The password field is required."
    ],
    "data": "Login validation failed"
}
```

### HTTP Request
`POST api/v1/auth/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | Verified Email of the User
        `password` | string |  required  | Password
    
<!-- END_2be1f0e022faf424f18f30275e61416e -->

<!-- START_fb963cd2a8a1aea083363e2d2f4d272a -->
## Registeration

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/signup" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"first_name":"qui","last_name":"qui","email":"consequatur","password":"quasi","currency_id":1,"image_id":10,"phone_no":8}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/signup"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "qui",
    "last_name": "qui",
    "email": "consequatur",
    "password": "quasi",
    "currency_id": 1,
    "image_id": 10,
    "phone_no": 8
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "user": {
            "first_name": "John",
            "last_name": "Doe",
            "currency_id": "1",
            "email": "JohnDoe@gmail.com",
            "phone_no": "1122334455",
            "image_id": null,
            "rating": 0,
            "updated_at": "2022-03-05T14:05:39.000000Z",
            "created_at": "2022-03-05T14:05:39.000000Z",
            "id": 13
        },
        "access_token": "eyeasd.."
    },
    "message": "You are registered successfully"
}
```
> Example response (422):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

### HTTP Request
`POST api/v1/auth/signup`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `first_name` | string |  required  | First Name
        `last_name` | string |  required  | Last Name
        `email` | string |  required  | A valid and Unique Email Address
        `password` | string |  required  | Password (Minimum 8 Characters)
        `currency_id` | integer |  optional  | Currency ID
        `image_id` | integer |  optional  | Image ID
        `phone_no` | integer |  required  | A valid Phone Number
    
<!-- END_fb963cd2a8a1aea083363e2d2f4d272a -->

<!-- START_715f1d73092629748c4397de566ea310 -->
## Logout user (Revoke the token)

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/auth/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": "",
    "message": "Successfully logged out"
}
```

### HTTP Request
`GET api/v1/auth/logout`


<!-- END_715f1d73092629748c4397de566ea310 -->

#Category


<!-- START_80420c095ed96da032c9eb419d7d6e2d -->
## Get a List of categories

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/categories" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"et"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/categories"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "et"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "name": "Home & Garden",
                "tariff": null,
                "image_url": "http:\/\/localhost\/categories\/Home&Garden.png"
            },
            {
                "id": 2,
                "name": "Luggage & Bags",
                "tariff": null,
                "image_url": "http:\/\/localhost\/categories\/Luggage&Bags.png"
            }
        ]
    },
    "message": "Get categories successfully"
}
```

### HTTP Request
`GET api/v1/categories`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Category Name
    
<!-- END_80420c095ed96da032c9eb419d7d6e2d -->

#Chat Room


<!-- START_1ee9f87e671c4d8b9fcfc037185787b8 -->
## Get Chat Rooms

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/chat-rooms/all" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/chat-rooms/all"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Chat rooms retrieved successfully",
    "data": []
}
```

### HTTP Request
`GET api/v1/chat-rooms/all`


<!-- END_1ee9f87e671c4d8b9fcfc037185787b8 -->

<!-- START_498c9c460745cbd7fdb5809b47ff5924 -->
## Get Chat Room Messages

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/chat-rooms/1/messages" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/chat-rooms/1/messages"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Chat room messages retrieved successfully",
    "data": []
}
```

### HTTP Request
`GET api/v1/chat-rooms/{id}/messages`


<!-- END_498c9c460745cbd7fdb5809b47ff5924 -->

#Counter Offer


<!-- START_03b7454fb4b314a6230693c759ce56c6 -->
## Create a Counter Offer for Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/offers/1/counter-offer" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"reward":13,"expiry_date":"saepe"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/offers/1/counter-offer"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reward": 13,
    "expiry_date": "saepe"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Counter Offer has been created successfully",
    "data": []
}
```
> Example response (401):

```json
{
    "success": false,
    "message": "You cant create Counter offer as you dont own this order",
    "data": []
}
```
> Example response (400):

```json
{
    "success": false,
    "message": "A Counter Offer already exists for this offer!",
    "data": []
}
```

### HTTP Request
`POST api/v1/offers/{id}/counter-offer`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `offer_id` |  optional  | integer required ID of the offer
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `reward` | integer |  required  | Reward price for the offer
        `expiry_date` | date |  required  | Expiry Date for the offer
    
<!-- END_03b7454fb4b314a6230693c759ce56c6 -->

<!-- START_5591f507c5bb5f32e7c4d7a6c84041d5 -->
## Reject a Counter Offer

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/offers/19/counter-offer/reject" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"reason_id":8,"counter_offer_id":12}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/offers/19/counter-offer/reject"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reason_id": 8,
    "counter_offer_id": 12
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Counter Offer rejected successfully",
    "data": []
}
```
> Example response (401):

```json
{
    "success": false,
    "message": "You are not authorized to reject counter offer for this Order!",
    "data": []
}
```

### HTTP Request
`POST api/v1/offers/{id}/counter-offer/reject`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | integer required ID of the counter offer
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `reason_id` | integer |  required  | ID of the Reason
        `counter_offer_id` | integer |  required  | ID of the counter offer
    
<!-- END_5591f507c5bb5f32e7c4d7a6c84041d5 -->

<!-- START_7b0529bff3294119fbd4b08cb404c61a -->
## Accept Counter Offer

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/offers/maxime/counter-offer/accept" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"counter_offer_id":11}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/offers/maxime/counter-offer/accept"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "counter_offer_id": 11
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Counter Offer has been accepted successfully",
    "data": []
}
```
> Example response (401):

```json
{
    "success": true,
    "message": "You are not authorized to accept counter offer for this Order!",
    "data": []
}
```
> Example response (400):

```json
{
    "success": true,
    "message": "This Counter Offer has already been Rejected",
    "data": []
}
```

### HTTP Request
`POST api/v1/offers/{id}/counter-offer/accept`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | integer required ID of the offer
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `counter_offer_id` | integer |  required  | ID of the counter offer
    
<!-- END_7b0529bff3294119fbd4b08cb404c61a -->

#Currency


<!-- START_d952fa63f57bfb9873a204032c46bef2 -->
## Display a listing of all the Currencies.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/currencies" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"eos"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/currencies"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "eos"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": {
        "success": true,
        "data": [
            {
                "id": 1,
                "name": "Currency1",
                "short_code": "Cur1",
                "symbol": "C1",
                "flag_url": null,
                "rate": 100
            }
        ]
    },
    "message": "Currencies retrieved successfully"
}
```

### HTTP Request
`GET api/v1/currencies`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  optional  | Name of the Currency. If no name is provided, all the currencies will be returned
    
<!-- END_d952fa63f57bfb9873a204032c46bef2 -->

<!-- START_939890e211f1c8911c52fd1a7226b4e9 -->
## Show a single Currency

@urlParam id required ID of the Currency

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/currencies/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/currencies/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known (SQL: select * from `currencies` where `id` = 1),712,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Connection.php"
    ]
}
```

### HTTP Request
`GET api/v1/currencies/{currency}`


<!-- END_939890e211f1c8911c52fd1a7226b4e9 -->

#Email Verification


<!-- START_3e4a08674c3c1aaa7a4e8aacbf86420a -->
## Verify Email

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/email/verify/1/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"id":3}'

```

```javascript
const url = new URL(
    "http://localhost/api/email/verify/1/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 3
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Email has been verified",
    "data": []
}
```
> Example response (400):

```json
{
    "success": true,
    "message": "Email has already been verified",
    "data": []
}
```

### HTTP Request
`GET api/email/verify/{id}/{hash}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | integer |  optional  | ID of the User
    
<!-- END_3e4a08674c3c1aaa7a4e8aacbf86420a -->

<!-- START_3e9eb45d05917fb6a5be739374033aa5 -->
## Resend Verification Email

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/email/verify/resend" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/email/verify/resend"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Please check your Inbox. We have sent the verification email again",
    "data": []
}
```
> Example response (400):

```json
{
    "success": true,
    "message": "Email has already been verified",
    "data": []
}
```

### HTTP Request
`POST api/email/verify/resend`


<!-- END_3e9eb45d05917fb6a5be739374033aa5 -->

#Notification


<!-- START_7bbc8bc2d5f3571e65b16230c91c1806 -->
## Get Notifications of the User

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/notifications" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"only_unread":false}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/notifications"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "only_unread": false
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET api/v1/notifications`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `only_unread` | boolean |  optional  | Return only unread notifications
    
<!-- END_7bbc8bc2d5f3571e65b16230c91c1806 -->

<!-- START_ef2694cc6f2c12004ecf9a4656a16807 -->
## Mark notification as Read

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/notifications/mark-as-read" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"id":"est","all_as_read":false}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/notifications/mark-as-read"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": "est",
    "all_as_read": false
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/notifications/mark-as-read`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `id` | string |  required  | ID of the notification
        `all_as_read` | boolean |  optional  | Should mark all notifications as read
    
<!-- END_ef2694cc6f2c12004ecf9a4656a16807 -->

#Offers


<!-- START_71db7b33ed071496bd705bd76a713caa -->
## Create a new Offer for Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/offers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"description":"aut","price":11,"reward":10,"expiry_date":"expedita","order_id":7,"trip_id":20,"currency_id":2}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/offers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "description": "aut",
    "price": 11,
    "reward": 10,
    "expiry_date": "expedita",
    "order_id": 7,
    "trip_id": 20,
    "currency_id": 2
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Offers created successfully",
    "data": []
}
```
> Example response (422):

```json
{
    "success": false,
    "message": "You cant create offer on your own order",
    "data": []
}
```

### HTTP Request
`POST api/v1/offers`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `description` | string |  required  | Description for the offer
        `price` | integer |  required  | Price for the offer
        `reward` | integer |  required  | Reward price for the offer
        `expiry_date` | date |  required  | Expiry Date for the offer
        `order_id` | integer |  required  | Order ID
        `trip_id` | integer |  required  | Trip ID
        `currency_id` | integer |  optional  | ID of the currency (Must be same as Order's Currency)
    
<!-- END_71db7b33ed071496bd705bd76a713caa -->

<!-- START_fd575fa3ec9e0082d61bbb9fd4b19a7d -->
## Update offer

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/offers/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"description":"mollitia","price":7,"reward":10,"expiry_date":"vel","order_id":3,"trip_id":15}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/offers/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "description": "mollitia",
    "price": 7,
    "reward": 10,
    "expiry_date": "vel",
    "order_id": 3,
    "trip_id": 15
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Offer updated successfully",
    "data": []
}
```
> Example response (404):

```json
{
    "success": false,
    "message": "Offer Error",
    "data": "No such offer found"
}
```
> Example response (400):

```json
{
    "success": false,
    "message": "Offer Error",
    "data": "Cant update offer now! No order exists for this offer"
}
```

### HTTP Request
`PUT api/v1/offers/{offer}`

`PATCH api/v1/offers/{offer}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  optional  | integer required ID of the trip
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `description` | string |  required  | Description for the offer
        `price` | integer |  required  | Price for the offer
        `reward` | integer |  required  | Reward price for the offer
        `expiry_date` | date |  required  | Expiry Date for the offer
        `order_id` | integer |  required  | Order ID
        `trip_id` | integer |  required  | Trip ID
    
<!-- END_fd575fa3ec9e0082d61bbb9fd4b19a7d -->

<!-- START_3a5b542b136bbbc753664320808c740a -->
## Reject Offer

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/offers/porro/reject" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"reason_id":"corporis"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/offers/porro/reject"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "reason_id": "corporis"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Offer has been rejected successfully.",
    "data": []
}
```
> Example response (404):

```json
{
    "success": false,
    "message": "No such offer found",
    "data": []
}
```
> Example response (400):

```json
{
    "success": false,
    "message": "Cant delete offer now",
    "data": []
}
```
> Example response (401):

```json
{
    "success": false,
    "message": [
        "You are not authorized to reject this offer"
    ],
    "data": []
}
```

### HTTP Request
`POST api/v1/offers/{id}/reject`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the offer
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `reason_id` | required |  optional  | Reason ID
    
<!-- END_3a5b542b136bbbc753664320808c740a -->

#Order


<!-- START_e6c60a27512af418c7bd8b0ee71e5f81 -->
## Check Trips for a specific Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/orders/ipsam/checkTripsForOrder" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/ipsam/checkTripsForOrder"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 6,
                "arrival_date": "2022-02-15",
                "status": "active",
                "from_city_id": 31594,
                "from_city": null,
                "destination_city_id": null,
                "destination_city": null,
                "completeSourceAddress": "Source",
                "completeDestinationAddress": "Dest",
                "totalOffer": 1,
                "accepterOffers": 0,
                "disputedOffers": 1
            }
        ]
    },
    "message": "Trips exist for this order"
}
```

### HTTP Request
`GET api/v1/orders/{id}/checkTripsForOrder`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the Order

<!-- END_e6c60a27512af418c7bd8b0ee71e5f81 -->

<!-- START_1c4f3722e3d4ae703a4ed0fa9b4cf240 -->
## Make an Order Disputed

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/orders/placeat/disputed" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/placeat/disputed"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "User 18 Order",
        "description": "This iis an Item description",
        "images": [
            "\/order_offer_images\/2.jpg",
            "\/order_offer_images\/3.jpg",
            "\/order_offer_images\/3.jpg"
        ],
        "duty_charges_images": [],
        "purchased_images": [],
        "category_name": "Home & Garden",
        "category_image_url": "http:\/\/localhost\/categories\/Home&Garden.png",
        "category_tariff": 20,
        "url": "https:\/\/www.sudoware.co",
        "weight": "1",
        "quantity": 1,
        "price": 150,
        "reward": 15,
        "with_box": 0,
        "needed_by": null,
        "status": "new",
        "createdBy": {
            "id": 1,
            "fullName": "Admin Admin",
            "rating": 0,
            "totalCompletedOrders": 0,
            "image": {}
        },
        "customer_rating": null,
        "customer_review": null,
        "traveler_rating": null,
        "traveler_review": null,
        "is_disputed": true,
        "completeSourceAddress": "",
        "completeDestinationAddress": "",
        "totalOffers": 13,
        "basePrice": 150,
        "basePriceCurrency": "$",
        "otherPrice": 150,
        "otherPriceCurrency": "$",
        "baseRewardPrice": 15,
        "baseRewardPriceCurrency": "$",
        "otherRewardPrice": 15,
        "otherRewardPriceCurrency": "$",
        "is_doorstep_delivery": 0,
        "can_revise": true,
        "my_offer": {
            "id": 7,
            "description": "Duchess! Oh! won't she be savage if I've kept her waiting!' Alice felt a little now and then, 'we.",
            "status": "accepted",
            "price": 675,
            "reward": 8,
            "service_charges": null,
            "basePriceCurrency": "$",
            "trip_id": 4,
            "order_id": 1,
            "expiry_date": "1991-01-12"
        }
    },
    "message": "Order status updated successfully"
}
```

### HTTP Request
`POST api/v1/orders/{id}/disputed`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the Order

<!-- END_1c4f3722e3d4ae703a4ed0fa9b4cf240 -->

<!-- START_f17534c7d021445aca716278c67aabb5 -->
## Update order status.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/orders/sed/updateStatus" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/sed/updateStatus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "User 18 Order",
        "description": "This iis an Item description",
        "images": [
            "\/order_offer_images\/2.jpg",
            "\/order_offer_images\/3.jpg",
            "\/order_offer_images\/3.jpg"
        ],
        "duty_charges_images": [],
        "purchased_images": [],
        "category_name": "Home & Garden",
        "category_image_url": "http:\/\/localhost\/categories\/Home&Garden.png",
        "category_tariff": 20,
        "url": "https:\/\/www.sudoware.co",
        "weight": "1",
        "quantity": 1,
        "price": 150,
        "reward": 15,
        "with_box": 0,
        "needed_by": null,
        "status": "new",
        "createdBy": {
            "id": 1,
            "fullName": "Admin Admin",
            "rating": 0,
            "totalCompletedOrders": 0,
            "image": {}
        },
        "customer_rating": null,
        "customer_review": null,
        "traveler_rating": null,
        "traveler_review": null,
        "is_disputed": 1,
        "completeSourceAddress": "",
        "completeDestinationAddress": "",
        "totalOffers": 13,
        "basePrice": 150,
        "basePriceCurrency": "$",
        "otherPrice": 150,
        "otherPriceCurrency": "$",
        "baseRewardPrice": 15,
        "baseRewardPriceCurrency": "$",
        "otherRewardPrice": 15,
        "otherRewardPriceCurrency": "$",
        "is_doorstep_delivery": 0,
        "can_revise": true,
        "my_offer": {
            "id": 7,
            "description": "Duchess! Oh! won't she be savage if I've kept her waiting!' Alice felt a little now and then, 'we.",
            "status": "accepted",
            "price": 675,
            "reward": 8,
            "service_charges": null,
            "basePriceCurrency": "$",
            "trip_id": 4,
            "order_id": 1,
            "expiry_date": "1991-01-12"
        }
    },
    "message": "Order status updated successfully"
}
```

### HTTP Request
`POST api/v1/orders/{id}/updateStatus`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the Order

<!-- END_f17534c7d021445aca716278c67aabb5 -->

<!-- START_6264caa5d412b6a94610c46366036c63 -->
## Accept offer for specific order.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/orders/dignissimos/acceptOffer" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"offer_id":12}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/dignissimos/acceptOffer"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "offer_id": 12
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
null
```

### HTTP Request
`POST api/v1/orders/{id}/acceptOffer`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the Order
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `offer_id` | integer |  required  | Id of the offer
    
<!-- END_6264caa5d412b6a94610c46366036c63 -->

<!-- START_9d9af4fe1c7abe278314c423c010c058 -->
## Get order offers.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/orders/iure/offers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/iure/offers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [
        {
            "id": 7,
            "description": "",
            "arrival_date": "2022-05-18",
            "expiry_date": "2022-05-09",
            "badges": [],
            "basePrice": 110,
            "basePriceCurrency": "$",
            "otherPrice": 20465.53,
            "otherPriceCurrency": "PKR",
            "baseRewardPrice": 5,
            "baseRewardPriceCurrency": "$",
            "otherRewardPrice": 930.25,
            "otherRewardPriceCurrency": "PKR",
            "createdBy": {
                "id": 3,
                "fullName": "Abdul Ahad",
                "rating": 0,
                "totalCompletedOrders": 0,
                "image": {}
            },
            "status": "closed",
            "has_counter_offer": true,
            "counter_offer": {
                "id": 5,
                "description": null,
                "status": "accepted",
                "currency_id": 2,
                "reward": 4,
                "expiry_date": "2022-05-26",
                "trip_id": 8,
                "order_id": 7,
                "user_id": 2,
                "offer_id": 7,
                "is_disabled": 0,
                "admin_review": null,
                "created_at": "2022-04-23T12:32:48.000000Z",
                "updated_at": "2022-04-23T12:33:51.000000Z",
                "deleted_at": null,
                "reason_id": null,
                "counter_traveler_service_charges": 0.12,
                "counter_other_traveler_service_charges": 22.33,
                "counter_traveler_earning": 3.88,
                "counter_other_traveler_earning": 721.88,
                "counter_customer_payable": 141.5,
                "counter_other_customer_payable": 26326.12,
                "other_reward": 744.2
            },
            "traveler_service_charges_percentage": 3,
            "customer_service_charges_percentage": 5,
            "customer_duty_charges_percentage": 20,
            "traveler_service_charges": 0.15,
            "other_traveler_service_charges": 27.91,
            "customer_service_charges": 5.5,
            "other_customer_service_charges": 1023.28,
            "customer_duty_charges": 22,
            "other_customer_duty_charges": 4093.11,
            "traveler_earning": 4.85,
            "other_traveler_earning": 902.34,
            "customer_payable": 142.5,
            "other_customer_payable": 26512.17
        }
    ],
    "message": "Order offers retrieved successfully"
}
```

### HTTP Request
`GET api/v1/orders/{id}/offers`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the order

<!-- END_9d9af4fe1c7abe278314c423c010c058 -->

<!-- START_25bf4092f5e200124a149897733aac34 -->
## Display List of Orders

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 98,
        "name": "16AprilOrder1",
        "description": "Testing counter offer",
        "images": [
            "625aa6a35098e1650108067_c7290d25-c621-4cfd-bcdb-4b8c3e20116e.jpg"
        ],
        "image_ids": [
            94
        ],
        "category_id": 4,
        "currency_id": 2,
        "from_city_id": 1,
        "from_city": {
            "id": 1,
            "name": "Islamabad",
            "state": "",
            "country": "",
            "flag_url": ""
        },
        "destination_city_id": 8,
        "destination_city": {
            "id": 8,
            "name": "Lahore",
            "state": "",
            "country": "",
            "flag_url": ""
        },
        "category_name": "Office Supplies",
        "category_image_url": "http:\/\/localhost\/categories\/OfficeSupplies.png",
        "category_tariff": 20,
        "url": null,
        "weight": "1",
        "quantity": 10,
        "price": 2000,
        "reward": 50,
        "with_box": 1,
        "needed_by": "2022-05-31",
        "createdBy": {
            "id": 24,
            "fullName": "Junaid Tahir dot com",
            "rating": 0,
            "totalCompletedOrders": 0,
            "image": []
        },
        "completeSourceAddress": "Islamabad",
        "completeDestinationAddress": "Lahore",
        "totalOffers": 2,
        "basePrice": 2000,
        "basePriceCurrency": "$",
        "otherPrice": 357400,
        "otherPriceCurrency": "PKR",
        "baseRewardPrice": 50,
        "baseRewardPriceCurrency": "$",
        "otherRewardPrice": 8935,
        "otherRewardPriceCurrency": "PKR",
        "is_doorstep_delivery": 1,
        "is_my_order": false,
        "currency": {
            "id": 2,
            "name": "United States Dollar",
            "short_code": "USD",
            "symbol": "$",
            "rate": 1,
            "country_id": 1,
            "created_at": "2022-03-11T17:41:17.000000Z",
            "updated_at": "2022-03-11T17:41:17.000000Z"
        },
        "has_counter_offer": "true",
        "can_revise": false
    },
    "message": "Orders retrieved successfully"
}
```

### HTTP Request
`GET api/v1/orders`


<!-- END_25bf4092f5e200124a149897733aac34 -->

<!-- START_c79cb2035f69ac8078c2cec9fc2fab4a -->
## Create New Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"aliquam","description":"recusandae","category_id":"sed","currency_id":"laudantium","weight":"illo","price":1,"reward":"aut","with_box":"et","needed_by":"sit","destination_city_id":12,"from_city_id":11,"is_doorstep_delivery":"consequatur","url":"in","images":"consequatur"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "aliquam",
    "description": "recusandae",
    "category_id": "sed",
    "currency_id": "laudantium",
    "weight": "illo",
    "price": 1,
    "reward": "aut",
    "with_box": "et",
    "needed_by": "sit",
    "destination_city_id": 12,
    "from_city_id": 11,
    "is_doorstep_delivery": "consequatur",
    "url": "in",
    "images": "consequatur"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Order Name 1",
        "description": "Order Description",
        "images": [
            "\/order_offer_images\/1.jpg",
            "\/order_offer_images\/3.jpg"
        ],
        "image_ids": [
            1,
            3
        ],
        "duty_charges_images": [],
        "purchased_images": [],
        "category_name": "Home & Garden",
        "category_image_url": "http:\/\/localhost\/categories\/Home&Garden.png",
        "category_tariff": 20,
        "url": null,
        "weight": "11",
        "quantity": "1",
        "price": 50,
        "reward": "12",
        "with_box": "1",
        "needed_by": "2022-02-02",
        "status": "new",
        "createdBy": {
            "id": 4,
            "fullName": "John Doe",
            "rating": 0,
            "totalCompletedOrders": 0,
            "image": {}
        },
        "customer_rating": null,
        "customer_review": null,
        "traveler_rating": null,
        "traveler_review": null,
        "is_disputed": null,
        "completeSourceAddress": "",
        "completeDestinationAddress": "Bombuflat",
        "totalOffers": 0,
        "basePrice": 50,
        "basePriceCurrency": "$",
        "otherPrice": 50,
        "otherPriceCurrency": "$",
        "baseRewardPrice": "12",
        "baseRewardPriceCurrency": "$",
        "otherRewardPrice": 12,
        "otherRewardPriceCurrency": "$",
        "pin_code": null,
        "pin_time_to_live": null,
        "is_doorstep_delivery": true
    },
    "message": "Order created successfully"
}
```

### HTTP Request
`POST api/v1/orders`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Name
        `description` | string |  required  | Description
        `category_id` | string |  required  | Category_ID
        `currency_id` | string |  required  | Currency_ID
        `weight` | string |  required  | Weight
        `price` | integer |  required  | Order Price
        `reward` | string |  required  | Order Reward
        `with_box` | Boolean |  required  | 
        `needed_by` | string |  required  | Needed By Name
        `destination_city_id` | integer |  required  | ID of the Destination City
        `from_city_id` | integer |  required  | ID of the From City
        `is_doorstep_delivery` | Boolean(Y/N) |  required  | Whether the order is doorstep delivery
        `url` | string |  optional  | A valid Url
        `images` | string |  optional  | A valid array containing valid images
    
<!-- END_c79cb2035f69ac8078c2cec9fc2fab4a -->

<!-- START_b4bbc4b1b4c4ddc2effe9e5e2475dd8d -->
## Display a single Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "User 18 Order",
        "description": "This iis an Item description",
        "images": [
            "\/order_offer_images\/2.jpg",
            "\/order_offer_images\/3.jpg",
            "\/order_offer_images\/3.jpg"
        ],
        "image_ids": [
            2,
            3,
            3
        ],
        "duty_charges_images": [],
        "purchased_images": [],
        "category_name": "Home & Garden",
        "category_image_url": "http:\/\/localhost\/categories\/Home&Garden.png",
        "category_tariff": 20,
        "url": "https:\/\/www.sudoware.co",
        "weight": "1",
        "quantity": 1,
        "price": 150,
        "reward": 15,
        "with_box": 0,
        "needed_by": null,
        "status": "new",
        "createdBy": {
            "id": 1,
            "fullName": "Admin Admin",
            "rating": 0,
            "totalCompletedOrders": 0,
            "image": {}
        },
        "customer_rating": null,
        "customer_review": null,
        "traveler_rating": null,
        "traveler_review": null,
        "is_disputed": 1,
        "completeSourceAddress": "",
        "completeDestinationAddress": "",
        "totalOffers": 0,
        "basePrice": 150,
        "basePriceCurrency": "$",
        "otherPrice": "",
        "otherPriceCurrency": "",
        "baseRewardPrice": 15,
        "baseRewardPriceCurrency": "$",
        "otherRewardPrice": "",
        "otherRewardPriceCurrency": "",
        "pin_code": null,
        "pin_time_to_live": null,
        "is_doorstep_delivery": 0
    },
    "message": "Order retrieved successfully"
}
```

### HTTP Request
`GET api/v1/orders/{order}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | Id of the Order

<!-- END_b4bbc4b1b4c4ddc2effe9e5e2475dd8d -->

<!-- START_2e6d997181b1c50b2b94eaa14b66f016 -->
## Update the Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"sunt","description":"voluptatem","category_id":"dolores","currency_id":"autem","weight":"quo","price":2,"reward":"fugiat","with_box":"laudantium","needed_by":"illo","destination_city_id":16,"is_doorstep_delivery":"sint","url":"ad","images":[]}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "sunt",
    "description": "voluptatem",
    "category_id": "dolores",
    "currency_id": "autem",
    "weight": "quo",
    "price": 2,
    "reward": "fugiat",
    "with_box": "laudantium",
    "needed_by": "illo",
    "destination_city_id": 16,
    "is_doorstep_delivery": "sint",
    "url": "ad",
    "images": []
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Order Name 1",
        "description": "Order Description",
        "images": [
            "\/order_offer_images\/1.jpg",
            "\/order_offer_images\/3.jpg"
        ],
        "image_ids": [
            1,
            3
        ],
        "duty_charges_images": [],
        "purchased_images": [],
        "category_name": "Home & Garden",
        "category_image_url": "http:\/\/localhost\/categories\/Home&Garden.png",
        "category_tariff": 20,
        "url": null,
        "weight": "11",
        "quantity": "1",
        "price": 50,
        "reward": "12",
        "with_box": "1",
        "needed_by": "2022-02-02",
        "status": "new",
        "createdBy": {
            "id": 4,
            "fullName": "John Doe",
            "rating": 0,
            "totalCompletedOrders": 0,
            "image": {}
        },
        "customer_rating": null,
        "customer_review": null,
        "traveler_rating": null,
        "traveler_review": null,
        "is_disputed": null,
        "completeSourceAddress": "",
        "completeDestinationAddress": "Bombuflat",
        "totalOffers": 0,
        "basePrice": 50,
        "basePriceCurrency": "$",
        "otherPrice": 50,
        "otherPriceCurrency": "$",
        "baseRewardPrice": "12",
        "baseRewardPriceCurrency": "$",
        "otherRewardPrice": 12,
        "otherRewardPriceCurrency": "$",
        "pin_code": null,
        "pin_time_to_live": null,
        "is_doorstep_delivery": "Y"
    },
    "message": "Order Updated successfully"
}
```

### HTTP Request
`PUT api/v1/orders/{order}`

`PATCH api/v1/orders/{order}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | Name
        `description` | string |  required  | Description
        `category_id` | string |  required  | Category_ID
        `currency_id` | string |  required  | Currency_ID
        `weight` | string |  required  | Weight
        `price` | integer |  required  | Order Price
        `reward` | string |  required  | Order Reward
        `with_box` | Boolean |  required  | 
        `needed_by` | string |  required  | Needed By Name
        `destination_city_id` | integer |  required  | ID of the Destination City
        `is_doorstep_delivery` | Boolean |  required  | Whether the order is doorstep delivery
        `url` | string |  optional  | A valid Url
        `images` | array |  optional  | Images Array
    
<!-- END_2e6d997181b1c50b2b94eaa14b66f016 -->

<!-- START_f34ad9d71f18dd67576cc6db60268192 -->
## Delete an Order

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/v1/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/orders/{order}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the Order

<!-- END_f34ad9d71f18dd67576cc6db60268192 -->

#Password Reset


<!-- START_1b8645d8e3fa4a892c8ba711f1726d64 -->
## Create token password reset

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/password/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"culpa"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/password/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "culpa"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [],
    "message": "We have e-mailed you password reset code!"
}
```

### HTTP Request
`POST api/v1/auth/password/create`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | Email of the User
    
<!-- END_1b8645d8e3fa4a892c8ba711f1726d64 -->

<!-- START_62e5f2424ee16bc86c51b9d12f309501 -->
## Find token password reset

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/auth/password/find/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"token":"repudiandae"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/password/find/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "token": "repudiandae"
}

fetch(url, {
    method: "GET",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [],
    "message": "You password reset token object"
}
```

### HTTP Request
`GET api/v1/auth/password/find/{token}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `token` | string |  required  | Password Token
    
<!-- END_62e5f2424ee16bc86c51b9d12f309501 -->

<!-- START_11eddaef605264cbbe21511716d17d51 -->
## Reset password

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"aut","password":"ut","token":"occaecati"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "aut",
    "password": "ut",
    "token": "occaecati"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [],
    "message": "Your Password have been changed successfully"
}
```
> Example response (404):

```json
{
    "success": false,
    "data": [],
    "message": "We can't find a user with that e-mail address"
}
```

### HTTP Request
`POST api/v1/auth/password/reset`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | Email of the User
        `password` | string |  required  | Password of the User
        `token` | string |  required  | Access token
    
<!-- END_11eddaef605264cbbe21511716d17d51 -->

#Payment


<!-- START_dd9c95992f153c29630c2ead0854f4c6 -->
## Process Payment of the Order

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/process-payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"order_id":8,"type":"quo"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/process-payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "order_id": 8,
    "type": "quo"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Order Payment has been completed successfully",
    "data": []
}
```
> Example response (400):

```json
{
    "success": false,
    "message": "Some error occured while processing payment.",
    "data": []
}
```

### HTTP Request
`POST api/v1/process-payment`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `order_id` | integer |  required  | ID of the Order
        `type` | string |  required  | Type of the Payment (Credit Card, Debit Card, Bank)
    
<!-- END_dd9c95992f153c29630c2ead0854f4c6 -->

#Reason


<!-- START_f2ebad43cab1f77cee672b1afe21a6e7 -->
## Get a List of Reasons for Offer/CounterOffer rejection

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/reasons" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reasons"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "description": "Order was late",
            "created_at": "2022-04-10T23:32:14.000000Z",
            "updated_at": "2022-04-10T23:32:14.000000Z"
        },
        {
            "id": 2,
            "description": "Trip was too long",
            "created_at": "2022-04-10T23:32:14.000000Z",
            "updated_at": "2022-04-10T23:32:14.000000Z"
        }
    ],
    "message": "Reasons Retrieved Successfully"
}
```

### HTTP Request
`GET api/v1/reasons`


<!-- END_f2ebad43cab1f77cee672b1afe21a6e7 -->

#Trip


<!-- START_8d4ef6d76018280d21154c5dc465461b -->
## Change status of Trip

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PATCH \
    "http://localhost/api/v1/trips/blanditiis/changeStatus" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":"officia"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/trips/blanditiis/changeStatus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": "officia"
}

fetch(url, {
    method: "PATCH",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 16,
        "arrival_date": "1970-01-01",
        "status": "active",
        "from_city_id": 2,
        "from_city": {
            "id": 2,
            "name": "Garacharma",
            "state": "Andaman and Nicobar Islands",
            "country": "",
            "flag_url": ""
        },
        "destination_city_id": null,
        "destination_city": {
            "id": 1,
            "name": "Bombuflat",
            "state": "Andaman and Nicobar Islands",
            "country": "",
            "flag_url": ""
        },
        "completeSourceAddress": "Garacharma",
        "completeDestinationAddress": "Bombuflat",
        "totalOffer": 0,
        "accepterOffers": 0,
        "disputedOffers": 0
    },
    "message": "Trip status changed successfully"
}
```
> Example response (422):

```json
{
    "success": false,
    "message": [
        "The status field is required."
    ],
    "data": "Validation failed"
}
```

### HTTP Request
`PATCH api/v1/trips/{id}/changeStatus`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the trip
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `status` | string |  required  | Must be either active, in_active, or completed
    
<!-- END_8d4ef6d76018280d21154c5dc465461b -->

<!-- START_ba4f56828539c04fae16c0f08eb96491 -->
## Display Orders for a Specific Trip

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/trips/quos/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/trips/quos/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "data": [],
        "links": {
            "first": "http:\/\/brrring.polt.pk\/api\/v1\/trips\/16\/orders?page=1",
            "last": "http:\/\/brrring.polt.pk\/api\/v1\/trips\/16\/orders?page=1",
            "prev": null,
            "next": null
        },
        "meta": {
            "current_page": 1,
            "from": null,
            "last_page": 1,
            "path": "http:\/\/brrring.polt.pk\/api\/v1\/trips\/16\/orders",
            "per_page": 10,
            "to": null,
            "total": 0
        }
    },
    "message": "Get trip orders successfully"
}
```

### HTTP Request
`GET api/v1/trips/{id}/orders`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the trip

<!-- END_ba4f56828539c04fae16c0f08eb96491 -->

<!-- START_570339c57cdc597f02d545467c67f7a8 -->
## View all Trips

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/trips" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/trips"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "data": [],
        "links": {
            "first": "http:\/\/brrring.polt.pk\/api\/v1\/trips?page=1",
            "last": "http:\/\/brrring.polt.pk\/api\/v1\/trips?page=1",
            "prev": null,
            "next": null
        },
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 1,
            "path": "http:\/\/brrring.polt.pk\/api\/v1\/trips",
            "per_page": 10,
            "to": 3,
            "total": 3
        }
    },
    "message": "Trips retrieved successfully"
}
```

### HTTP Request
`GET api/v1/trips`


<!-- END_570339c57cdc597f02d545467c67f7a8 -->

<!-- START_b0bfe967e103764914eff25d075c572c -->
## Store a new Trip

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/trips" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"arrival_date":"quasi","from_city_id":9,"destination_city_id":20}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/trips"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "arrival_date": "quasi",
    "from_city_id": 9,
    "destination_city_id": 20
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 16,
        "arrival_date": "1970-01-01 00:00:00",
        "status": "active",
        "from_city_id": "2",
        "from_city": {
            "id": 2,
            "name": "Garacharma",
            "state": "Andaman and Nicobar Islands",
            "country": "",
            "flag_url": ""
        },
        "destination_city_id": null,
        "destination_city": {
            "id": 1,
            "name": "Bombuflat",
            "state": "Andaman and Nicobar Islands",
            "country": "",
            "flag_url": ""
        },
        "completeSourceAddress": "Garacharma",
        "completeDestinationAddress": "Bombuflat",
        "totalOffer": 0,
        "accepterOffers": 0,
        "disputedOffers": 0
    },
    "message": "Trip created successfully"
}
```
> Example response (422):

```json
{
    "success": false,
    "message": [
        "The from city id field is required."
    ],
    "data": "Validation failed"
}
```

### HTTP Request
`POST api/v1/trips`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `arrival_date` | string |  required  | Arrival Date of the Trip
        `from_city_id` | integer |  required  | The ID of from City
        `destination_city_id` | integer |  required  | The ID of destination city
    
<!-- END_b0bfe967e103764914eff25d075c572c -->

<!-- START_18a55de27e6b4e429ded5fdedbab7cf4 -->
## Update the specified trip.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/trips/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"arrival_date":"quidem","from_city_id":15,"destination_city_id":4}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/trips/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "arrival_date": "quidem",
    "from_city_id": 15,
    "destination_city_id": 4
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 16,
        "arrival_date": "1970-01-01 00:00:00",
        "status": "in_active",
        "from_city_id": "2",
        "from_city": {
            "id": 2,
            "name": "Garacharma",
            "state": "Andaman and Nicobar Islands",
            "country": "",
            "flag_url": ""
        },
        "destination_city_id": null,
        "destination_city": {
            "id": 1,
            "name": "Bombuflat",
            "state": "Andaman and Nicobar Islands",
            "country": "",
            "flag_url": ""
        },
        "completeSourceAddress": "Garacharma",
        "completeDestinationAddress": "Bombuflat",
        "totalOffer": 0,
        "accepterOffers": 0,
        "disputedOffers": 0
    },
    "message": "Trip updated successfully"
}
```

### HTTP Request
`PUT api/v1/trips/{trip}`

`PATCH api/v1/trips/{trip}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the trip
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `arrival_date` | string |  required  | Arrival Date of the Trip
        `from_city_id` | integer |  required  | The ID of from City
        `destination_city_id` | integer |  required  | The ID of destination city
    
<!-- END_18a55de27e6b4e429ded5fdedbab7cf4 -->

<!-- START_819b84a295a6859066bc63328b8e8eff -->
## Remove the specified trip.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/v1/trips/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/trips/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/trips/{trip}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `id` |  required  | ID of the trip

<!-- END_819b84a295a6859066bc63328b8e8eff -->

#User Profile


<!-- START_dfc86c88da9f3ec67b5fe014cb2a6e5a -->
## Check email of the logged in user is verified or not

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/auth/is-email-verified" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/is-email-verified"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "",
    "data": [
        {
            "is_email_verified": true
        }
    ]
}
```

### HTTP Request
`GET api/v1/auth/is-email-verified`


<!-- END_dfc86c88da9f3ec67b5fe014cb2a6e5a -->

<!-- START_939d3f5c7de6e9ac3d5a8feb831ab426 -->
## Refresh Token

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/refresh-token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"token":"laudantium"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/refresh-token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "token": "laudantium"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Token has been refreshed successfully",
    "data": []
}
```

### HTTP Request
`POST api/v1/auth/refresh-token`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `token` | string |  required  | The JWT token
    
<!-- END_939d3f5c7de6e9ac3d5a8feb831ab426 -->

<!-- START_883f9ae4bac517cd7b59b06a3b96d1bb -->
## Update Currencies

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/auth/update_currencies" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/update_currencies"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "message": "Currencies have been updated successfully",
    "data": []
}
```

### HTTP Request
`GET api/v1/auth/update_currencies`


<!-- END_883f9ae4bac517cd7b59b06a3b96d1bb -->

<!-- START_18ba9af174ed5681daaeb935699371e2 -->
## Update user Profile

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/update-profile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"first_name":"mollitia","last_name":"dolores","currency_id":"sint","phone_no":"minus","password":"non","password_confirmation":"quae","email":"quia"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/update-profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "mollitia",
    "last_name": "dolores",
    "currency_id": "sint",
    "phone_no": "minus",
    "password": "non",
    "password_confirmation": "quae",
    "email": "quia"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 20,
            "role_id": null,
            "name": "",
            "first_name": "Faheem",
            "last_name": "Nawazz",
            "email": "mfahimnawaz@gmail.com",
            "avatar": "users\/default.png",
            "phone_no": "1122334455",
            "rating": 0,
            "currency_id": "1",
            "image_id": null,
            "settings": null,
            "email_verified_at": "2022-03-09T21:06:14.000000Z",
            "is_disabled": 0,
            "admin_review": null,
            "device_token": null,
            "created_at": "2022-03-09T21:05:56.000000Z",
            "updated_at": "2022-03-15T15:48:50.000000Z",
            "deleted_at": null,
            "currency": {
                "id": 1,
                "name": "Pakistani Rupee",
                "short_code": "PKR",
                "symbol": "PKR",
                "rate": 178.7,
                "country_id": 1,
                "created_at": "2022-03-10T15:40:56.000000Z",
                "updated_at": "2022-03-10T15:40:56.000000Z"
            },
            "image": null
        }
    },
    "message": "User Profile updated successfully"
}
```

### HTTP Request
`POST api/v1/auth/update-profile`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `first_name` | string |  required  | First Name of the User
        `last_name` | string |  required  | Last Name of the User
        `currency_id` | string |  required  | ID of the Currency
        `phone_no` | string |  required  | A valid phone number
        `password` | string |  optional  | A valid password
        `password_confirmation` | string |  optional  | Same as password if password was provided
        `email` | string |  optional  | A valid Email
    
<!-- END_18ba9af174ed5681daaeb935699371e2 -->

<!-- START_1a9d5edadfbeb718751906a2c7e2d559 -->
## Upload Avatar

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/auth/upload_avatar" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"image":"minima"}'

```

```javascript
const url = new URL(
    "http://localhost/api/v1/auth/upload_avatar"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "image": "minima"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "meessge": "Avatar has been uploaded successfully",
    "data": []
}
```

### HTTP Request
`POST api/v1/auth/upload_avatar`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `image` | image |  required  | A valid Image (jpg, jpeg, png, bmp, pdf) having max 5120KB size
    
<!-- END_1a9d5edadfbeb718751906a2c7e2d559 -->

#general


<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET oauth/authorize`


<!-- END_0c068b4037fb2e47e71bd44bd36e3e2a -->

<!-- START_e48cc6a0b45dd21b7076ab2c03908687 -->
## Approve the authorization request.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/authorize`


<!-- END_e48cc6a0b45dd21b7076ab2c03908687 -->

<!-- START_de5d7581ef1275fce2a229b6b6eaad9c -->
## Deny the authorization request.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/authorize" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/authorize"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/authorize`


<!-- END_de5d7581ef1275fce2a229b6b6eaad9c -->

<!-- START_a09d20357336aa979ecd8e3972ac9168 -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token`


<!-- END_a09d20357336aa979ecd8e3972ac9168 -->

<!-- START_d6a56149547e03307199e39e03e12d1c -->
## Get all of the authorized tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET oauth/tokens`


<!-- END_d6a56149547e03307199e39e03e12d1c -->

<!-- START_a9a802c25737cca5324125e5f60b72a5 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/tokens/{token_id}`


<!-- END_a9a802c25737cca5324125e5f60b72a5 -->

<!-- START_abe905e69f5d002aa7d26f433676d623 -->
## Get a fresh transient token cookie for the authenticated user.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/token/refresh" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/token/refresh"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/token/refresh`


<!-- END_abe905e69f5d002aa7d26f433676d623 -->

<!-- START_babcfe12d87b8708f5985e9d39ba8f2c -->
## Get all of the clients for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET oauth/clients`


<!-- END_babcfe12d87b8708f5985e9d39ba8f2c -->

<!-- START_9eabf8d6e4ab449c24c503fcb42fba82 -->
## Store a new client.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/clients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/clients`


<!-- END_9eabf8d6e4ab449c24c503fcb42fba82 -->

<!-- START_784aec390a455073fc7464335c1defa1 -->
## Update the given client.

> Example request:

```bash
curl -X PUT \
    "http://localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT oauth/clients/{client_id}`


<!-- END_784aec390a455073fc7464335c1defa1 -->

<!-- START_1f65a511dd86ba0541d7ba13ca57e364 -->
## Delete the given client.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/clients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/clients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/clients/{client_id}`


<!-- END_1f65a511dd86ba0541d7ba13ca57e364 -->

<!-- START_9e281bd3a1eb1d9eb63190c8effb607c -->
## Get all of the available scopes for the application.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/scopes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/scopes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET oauth/scopes`


<!-- END_9e281bd3a1eb1d9eb63190c8effb607c -->

<!-- START_9b2a7699ce6214a79e0fd8107f8b1c9e -->
## Get all of the personal access tokens for the authenticated user.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET oauth/personal-access-tokens`


<!-- END_9b2a7699ce6214a79e0fd8107f8b1c9e -->

<!-- START_a8dd9c0a5583742e671711f9bb3ee406 -->
## Create a new personal access token for the user.

> Example request:

```bash
curl -X POST \
    "http://localhost/oauth/personal-access-tokens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST oauth/personal-access-tokens`


<!-- END_a8dd9c0a5583742e671711f9bb3ee406 -->

<!-- START_bae65df80fd9d72a01439241a9ea20d0 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/oauth/personal-access-tokens/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/oauth/personal-access-tokens/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE oauth/personal-access-tokens/{token_id}`


<!-- END_bae65df80fd9d72a01439241a9ea20d0 -->

<!-- START_db24fa8ceecd2fd884ffca214ad57acc -->
## Authenticate the request for channel access.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/broadcasting/auth" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/broadcasting/auth"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/broadcasting/auth`

`POST api/v1/broadcasting/auth`


<!-- END_db24fa8ceecd2fd884ffca214ad57acc -->

<!-- START_cb597ab0518505c008912ed3428b60f9 -->
## api/v1/cities
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/cities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/cities"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known (SQL: select count(*) as aggregate from `cities`),712,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Connection.php"
    ]
}
```

### HTTP Request
`GET api/v1/cities`


<!-- END_cb597ab0518505c008912ed3428b60f9 -->

<!-- START_178f2d77417d58bac2044ca0f2d4f15a -->
## api/v1/reports/reasons
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/reports/reasons" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports/reasons"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/reports/reasons`


<!-- END_178f2d77417d58bac2044ca0f2d4f15a -->

<!-- START_98e942c6ea56c297835cbb627a12100d -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/reports" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/reports`


<!-- END_98e942c6ea56c297835cbb627a12100d -->

<!-- START_26eaab681a7c48f19ffa421f0fca4fdb -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/reports/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/reports/create`


<!-- END_26eaab681a7c48f19ffa421f0fca4fdb -->

<!-- START_2248338be892a9b129658f2b9bd359e0 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/reports" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/reports`


<!-- END_2248338be892a9b129658f2b9bd359e0 -->

<!-- START_0b45dfdf779d14bfb30526faeaac29cb -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/reports/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/reports/{report}`


<!-- END_0b45dfdf779d14bfb30526faeaac29cb -->

<!-- START_c93a72ee31e38484e814da36349e9a1a -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/reports/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/reports/{report}/edit`


<!-- END_c93a72ee31e38484e814da36349e9a1a -->

<!-- START_9efa29b642b620289c72b7e48e5071a8 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/reports/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/reports/{report}`

`PATCH api/v1/reports/{report}`


<!-- END_9efa29b642b620289c72b7e48e5071a8 -->

<!-- START_88a49e0657160ce8e0005636685bb75b -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/v1/reports/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/reports/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/reports/{report}`


<!-- END_88a49e0657160ce8e0005636685bb75b -->

<!-- START_219843b7bf3f97912027625b813fc70c -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/advertisements" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/advertisements`


<!-- END_219843b7bf3f97912027625b813fc70c -->

<!-- START_498bbebe09d812d30ebde01a80e49daf -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/advertisements/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/advertisements/create`


<!-- END_498bbebe09d812d30ebde01a80e49daf -->

<!-- START_0a6503badcbdf6af85a0dc2903797d21 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/advertisements" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/advertisements`


<!-- END_0a6503badcbdf6af85a0dc2903797d21 -->

<!-- START_9e45782c02a0820a57b38ea2052ed8b6 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/advertisements/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/advertisements/{advertisement}`


<!-- END_9e45782c02a0820a57b38ea2052ed8b6 -->

<!-- START_d57cfb5858b16cbc15e074657eb05b7d -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/advertisements/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/advertisements/{advertisement}/edit`


<!-- END_d57cfb5858b16cbc15e074657eb05b7d -->

<!-- START_8b3b6ba8d61cc707d1753c0c78e6b969 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/advertisements/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/advertisements/{advertisement}`

`PATCH api/v1/advertisements/{advertisement}`


<!-- END_8b3b6ba8d61cc707d1753c0c78e6b969 -->

<!-- START_da5770e8733053eac92d0e8f1591ee99 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/v1/advertisements/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/advertisements/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/advertisements/{advertisement}`


<!-- END_da5770e8733053eac92d0e8f1591ee99 -->

<!-- START_0f19c59bad3f90db68986ce2ad86a836 -->
## api/v1/wallet
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/wallet" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/wallet"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/wallet`


<!-- END_0f19c59bad3f90db68986ce2ad86a836 -->

<!-- START_989113473f00ed605f3bec5129f39a55 -->
## api/v1/wallet/credit
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/wallet/credit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/wallet/credit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/wallet/credit`


<!-- END_989113473f00ed605f3bec5129f39a55 -->

<!-- START_f1ccfafb435109f192aecbcbe10841a4 -->
## api/v1/wallet/debit
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/wallet/debit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/wallet/debit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/wallet/debit`


<!-- END_f1ccfafb435109f192aecbcbe10841a4 -->

<!-- START_6cc8ba40f4998c4b87c492c14afc6ee0 -->
## api/v1/wallet/status
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/wallet/status" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/wallet/status"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/wallet/status`


<!-- END_6cc8ba40f4998c4b87c492c14afc6ee0 -->

<!-- START_e91b0af0278029e1f6c103542135b6be -->
## api/v1/transactions
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/transactions`


<!-- END_e91b0af0278029e1f6c103542135b6be -->

<!-- START_f9df9264f2c8ff953268b20ad15566f7 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/messages" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/messages`


<!-- END_f9df9264f2c8ff953268b20ad15566f7 -->

<!-- START_842d03b5b2f12d6c31cb4b52e04dc442 -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/messages/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/messages/create`


<!-- END_842d03b5b2f12d6c31cb4b52e04dc442 -->

<!-- START_8a89d9625ace6829b2cd027e311faf0d -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/messages" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/messages`


<!-- END_8a89d9625ace6829b2cd027e311faf0d -->

<!-- START_741ad6d453a7d1a8483cc2353146abbe -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/messages/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/messages/{message}`


<!-- END_741ad6d453a7d1a8483cc2353146abbe -->

<!-- START_51ccf4abe38e2dacd0d228e50c9521c0 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/messages/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/messages/{message}/edit`


<!-- END_51ccf4abe38e2dacd0d228e50c9521c0 -->

<!-- START_86e4e9bc0a74549c444aaf8d92cbfe1d -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/messages/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/messages/{message}`

`PATCH api/v1/messages/{message}`


<!-- END_86e4e9bc0a74549c444aaf8d92cbfe1d -->

<!-- START_43b19fbb63d7c4904f02ac79cc6300dc -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/v1/messages/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/messages/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/messages/{message}`


<!-- END_43b19fbb63d7c4904f02ac79cc6300dc -->

<!-- START_69d7a9b6c8f92d17d78f8f750e6e67b4 -->
## api/v1/save-token
> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/save-token" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/save-token"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/save-token`


<!-- END_69d7a9b6c8f92d17d78f8f750e6e67b4 -->

<!-- START_f92b2ee5e990dc284e770e3dcc4a8989 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/images" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/images`


<!-- END_f92b2ee5e990dc284e770e3dcc4a8989 -->

<!-- START_ae9bf80d50237b70e68a008cf49a225f -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/images/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/images/create`


<!-- END_ae9bf80d50237b70e68a008cf49a225f -->

<!-- START_796d8cbd139f7944a430e626c3e5acc4 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/v1/images" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/images`


<!-- END_796d8cbd139f7944a430e626c3e5acc4 -->

<!-- START_0c0d315e430a269d03fbe6fcdebee0e3 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/images/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/images/{image}`


<!-- END_0c0d315e430a269d03fbe6fcdebee0e3 -->

<!-- START_3118955809eba45ee8239481df5a6237 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/v1/images/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET api/v1/images/{image}/edit`


<!-- END_3118955809eba45ee8239481df5a6237 -->

<!-- START_835d6bf8f59a2749ad968731f2798695 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/v1/images/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/v1/images/{image}`

`PATCH api/v1/images/{image}`


<!-- END_835d6bf8f59a2749ad968731f2798695 -->

<!-- START_91e97babe411ae6cf71ffe46be046980 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/v1/images/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/v1/images/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/v1/images/{image}`


<!-- END_91e97babe411ae6cf71ffe46be046980 -->

<!-- START_d9a2f55a9f0088215e6b81753889e5bf -->
## offers/{offer_id}/process_payment
> Example request:

```bash
curl -X GET \
    -G "http://localhost/offers/1/process_payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers/1/process_payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known (SQL: select * from `offers` where `id` = 1 and `offers`.`deleted_at` is null limit 1),712,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Connection.php"
    ]
}
```

### HTTP Request
`GET offers/{offer_id}/process_payment`


<!-- END_d9a2f55a9f0088215e6b81753889e5bf -->

<!-- START_344125eb1aa8a7a8dc7652e45594004d -->
## socket
> Example request:

```bash
curl -X GET \
    -G "http://localhost/socket" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/socket"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET socket`


<!-- END_344125eb1aa8a7a8dc7652e45594004d -->

<!-- START_45bc5de873f2b0d5fe0de995d73a89a7 -->
## getAllCategories
> Example request:

```bash
curl -X GET \
    -G "http://localhost/getAllCategories" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/getAllCategories"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known (SQL: select * from `categories`),712,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Connection.php"
    ]
}
```

### HTTP Request
`GET getAllCategories`


<!-- END_45bc5de873f2b0d5fe0de995d73a89a7 -->

<!-- START_64ec8f1c671477917dfa9959acb45432 -->
## getOrderAllStatuses
> Example request:

```bash
curl -X GET \
    -G "http://localhost/getOrderAllStatuses" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/getOrderAllStatuses"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "id": "new",
        "name": "new"
    },
    {
        "id": "tip",
        "name": "tip"
    },
    {
        "id": "paid",
        "name": "paid"
    },
    {
        "id": "purchased",
        "name": "purchased"
    },
    {
        "id": "tracking",
        "name": "tracking"
    },
    {
        "id": "handed",
        "handed": "handed"
    },
    {
        "id": "recieved",
        "name": "recieved"
    },
    {
        "id": "scanned",
        "name": "scanned"
    },
    {
        "id": "traveler_rated",
        "name": "traveler_rated"
    },
    {
        "id": "customer_rated",
        "name": "customer_rated"
    },
    {
        "id": "rated",
        "name": "rated"
    },
    {
        "id": "traveler_paid",
        "name": "traveler_paid"
    }
]
```

### HTTP Request
`GET getOrderAllStatuses`


<!-- END_64ec8f1c671477917dfa9959acb45432 -->

<!-- START_f362692022bb62ae877ea955906c3810 -->
## getOfferAllStatuses
> Example request:

```bash
curl -X GET \
    -G "http://localhost/getOfferAllStatuses" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/getOfferAllStatuses"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "id": "unaccepted",
        "name": "unaccepted"
    },
    {
        "id": "accepted",
        "name": "accepted"
    },
    {
        "id": "stale",
        "name": "stale"
    },
    {
        "id": "rejected",
        "name": "rejected"
    }
]
```

### HTTP Request
`GET getOfferAllStatuses`


<!-- END_f362692022bb62ae877ea955906c3810 -->

<!-- START_83ff2c06d4eed9608cfb6cef37d1b5f8 -->
## getAllCurrencies
> Example request:

```bash
curl -X GET \
    -G "http://localhost/getAllCurrencies" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/getAllCurrencies"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known (SQL: select * from `currencies`),712,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Connection.php"
    ]
}
```

### HTTP Request
`GET getAllCurrencies`


<!-- END_83ff2c06d4eed9608cfb6cef37d1b5f8 -->

<!-- START_e416f3560da11da5a7b7f8e800318ba3 -->
## getAllCities
> Example request:

```bash
curl -X GET \
    -G "http://localhost/getAllCities" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/getAllCities"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo failed: Name or service not known (SQL: select * from `cities`),712,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Database\/Connection.php"
    ]
}
```

### HTTP Request
`GET getAllCities`


<!-- END_e416f3560da11da5a7b7f8e800318ba3 -->

<!-- START_66e08d3cc8222573018fed49e121e96d -->
## Show the application&#039;s login form.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET login`


<!-- END_66e08d3cc8222573018fed49e121e96d -->

<!-- START_ba35aa39474cb98cfb31829e70eb8b74 -->
## Handle a login request to the application.

> Example request:

```bash
curl -X POST \
    "http://localhost/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST login`


<!-- END_ba35aa39474cb98cfb31829e70eb8b74 -->

<!-- START_e65925f23b9bc6b93d9356895f29f80c -->
## logout
> Example request:

```bash
curl -X POST \
    "http://localhost/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST logout`


<!-- END_e65925f23b9bc6b93d9356895f29f80c -->

<!-- START_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->
## Show the application registration form.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET register`


<!-- END_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->

<!-- START_d7aad7b5ac127700500280d511a3db01 -->
## Handle a registration request for the application.

> Example request:

```bash
curl -X POST \
    "http://localhost/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST register`


<!-- END_d7aad7b5ac127700500280d511a3db01 -->

<!-- START_d72797bae6d0b1f3a341ebb1f8900441 -->
## Display the form to request a password reset link.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset`


<!-- END_d72797bae6d0b1f3a341ebb1f8900441 -->

<!-- START_feb40f06a93c80d742181b6ffb6b734e -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://localhost/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/email`


<!-- END_feb40f06a93c80d742181b6ffb6b734e -->

<!-- START_e1605a6e5ceee9d1aeb7729216635fd7 -->
## Display the password reset view for the given token.

If no token is present, display the link request form.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/reset/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset/{token}`


<!-- END_e1605a6e5ceee9d1aeb7729216635fd7 -->

<!-- START_cafb407b7a846b31491f97719bb15aef -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://localhost/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/reset`


<!-- END_cafb407b7a846b31491f97719bb15aef -->

<!-- START_b77aedc454e9471a35dcb175278ec997 -->
## Display the password confirmation view.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/confirm"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET password/confirm`


<!-- END_b77aedc454e9471a35dcb175278ec997 -->

<!-- START_54462d3613f2262e741142161c0e6fea -->
## Confirm the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://localhost/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/password/confirm"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/confirm`


<!-- END_54462d3613f2262e741142161c0e6fea -->

<!-- START_4c8648652b4a537453b0cb352fa3f763 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/users/1/ban" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users/1/ban"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET users/{id}/ban`


<!-- END_4c8648652b4a537453b0cb352fa3f763 -->

<!-- START_89966bfb9ab533cc3249b91a9090d3dc -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET users`


<!-- END_89966bfb9ab533cc3249b91a9090d3dc -->

<!-- START_04094f136cb91c117bde084191e6859d -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/users/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET users/create`


<!-- END_04094f136cb91c117bde084191e6859d -->

<!-- START_57a8a4ba671355511e22780b1b63690e -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST users`


<!-- END_57a8a4ba671355511e22780b1b63690e -->

<!-- START_5693ac2f2e21af3ebc471cd5a6244460 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET users/{user}`


<!-- END_5693ac2f2e21af3ebc471cd5a6244460 -->

<!-- START_9c6e6c2d3215b1ba7d13468e7cd95e62 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/users/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET users/{user}/edit`


<!-- END_9c6e6c2d3215b1ba7d13468e7cd95e62 -->

<!-- START_7fe085c671e1b3d51e86136538b1d63f -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT users/{user}`

`PATCH users/{user}`


<!-- END_7fe085c671e1b3d51e86136538b1d63f -->

<!-- START_a948aef61c80bf96137d023464fde21f -->
## users/{user}
> Example request:

```bash
curl -X DELETE \
    "http://localhost/users/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/users/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE users/{user}`


<!-- END_a948aef61c80bf96137d023464fde21f -->

<!-- START_b5c3d96b6f223c292187fb2933f21034 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET orders`


<!-- END_b5c3d96b6f223c292187fb2933f21034 -->

<!-- START_56760860f40bdc3b42f7de9b9a006e5e -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/orders/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET orders/create`


<!-- END_56760860f40bdc3b42f7de9b9a006e5e -->

<!-- START_ec29d74de87750d93ffc5c2316881ba2 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/orders" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST orders`


<!-- END_ec29d74de87750d93ffc5c2316881ba2 -->

<!-- START_8ebabf804d4d9c276a852395bcb61678 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET orders/{order}`


<!-- END_8ebabf804d4d9c276a852395bcb61678 -->

<!-- START_59fe1ae8219dc0c0c3db197c20b50bd1 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/orders/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET orders/{order}/edit`


<!-- END_59fe1ae8219dc0c0c3db197c20b50bd1 -->

<!-- START_9144c4cefdb1ada60d895a1766f1710f -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT orders/{order}`

`PATCH orders/{order}`


<!-- END_9144c4cefdb1ada60d895a1766f1710f -->

<!-- START_29c8f0bec78089caa3791b727dc038b0 -->
## orders/{order}
> Example request:

```bash
curl -X DELETE \
    "http://localhost/orders/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/orders/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE orders/{order}`


<!-- END_29c8f0bec78089caa3791b727dc038b0 -->

<!-- START_4d56807b9a13a04ee8e8860e170cab6c -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/offers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET offers`


<!-- END_4d56807b9a13a04ee8e8860e170cab6c -->

<!-- START_b90d07468008d4d355c4e323c416c1db -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/offers/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET offers/create`


<!-- END_b90d07468008d4d355c4e323c416c1db -->

<!-- START_7c812019b42809e339d8836a8cdcaedd -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/offers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST offers`


<!-- END_7c812019b42809e339d8836a8cdcaedd -->

<!-- START_e95c9a82812a9b27c393f8b8b953703b -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/offers/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET offers/{offer}`


<!-- END_e95c9a82812a9b27c393f8b8b953703b -->

<!-- START_e208db3537749fe099f89380da8d0e23 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/offers/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET offers/{offer}/edit`


<!-- END_e208db3537749fe099f89380da8d0e23 -->

<!-- START_c8749811dd6f11f223d115632eda4dd3 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/offers/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT offers/{offer}`

`PATCH offers/{offer}`


<!-- END_c8749811dd6f11f223d115632eda4dd3 -->

<!-- START_1ad0be4401914bd2c15319fdf56b3592 -->
## offers/{offer}
> Example request:

```bash
curl -X DELETE \
    "http://localhost/offers/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/offers/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE offers/{offer}`


<!-- END_1ad0be4401914bd2c15319fdf56b3592 -->

<!-- START_1c0ac68b1aa6d477749493a3b9de46f9 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/reports" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET reports`


<!-- END_1c0ac68b1aa6d477749493a3b9de46f9 -->

<!-- START_abc136da98a15d10fc73a0667720020d -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/reports/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET reports/create`


<!-- END_abc136da98a15d10fc73a0667720020d -->

<!-- START_fb3d715bfa4d5e947b5204ac082af496 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/reports" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST reports`


<!-- END_fb3d715bfa4d5e947b5204ac082af496 -->

<!-- START_cc4cc5cbae4dba323fed219b9649c473 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/reports/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET reports/{report}`


<!-- END_cc4cc5cbae4dba323fed219b9649c473 -->

<!-- START_f434a45e15dae2a6cae778566a16b278 -->
## Show the form for editing the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/reports/1/edit" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports/1/edit"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET reports/{report}/edit`


<!-- END_f434a45e15dae2a6cae778566a16b278 -->

<!-- START_04479a2c2310f885180ede9a5f512b19 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/reports/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT reports/{report}`

`PATCH reports/{report}`


<!-- END_04479a2c2310f885180ede9a5f512b19 -->

<!-- START_e8290e60db2f2d2eaa49ceef17244e11 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/reports/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/reports/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE reports/{report}`


<!-- END_e8290e60db2f2d2eaa49ceef17244e11 -->

<!-- START_cb859c8e84c35d7133b6a6c8eac253f8 -->
## Show the application dashboard.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/home"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
{
    "success": false,
    "message": "Exception",
    "data": [
        "Unauthenticated.,82,\/home\/faheem\/Coding\/Projects\/brrring\/vendor\/laravel\/framework\/src\/Illuminate\/Auth\/Middleware\/Authenticate.php"
    ]
}
```

### HTTP Request
`GET home`


<!-- END_cb859c8e84c35d7133b6a6c8eac253f8 -->


