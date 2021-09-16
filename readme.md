# Run on local guideline

## Prerequisites

- Docker CE + Docker compose (19.03 or later)
```bash
$ docker --version
Docker version 19.03.8, build afacb8b7f0

$ docker-compose --version
docker-compose version 1.25.5, build 8a1c60f6
```
- Node 12.14.0 (or later)
```bash
$ node -v
v12.14.0

$ npm -v
6.14.7
```


## Clone repo
Create a new folder and clone repo
```bash
$ mkdir ~/s2ecom-order-service
$ cd ~/s2ecom-order-service
```

```bash
$ git clone git@bitbucket.org:vmodev/s2ecom-order-service.git
```

## Start database service

This service using **MySql Database** (recommended version = 5.7)
Please see authentication detail in `docker-compose.yml` file below

`file [git repo]/env/local/mysql-3308/docker-compose.yml`
```yml
version: '2'
services:
  mysql57_3308:
    image: mysql:5.7
    container_name: mysql_s2ecom_3308
    restart: always
    environment:
      - MYSQL_DATABASE=s2ecom_order
      - MYSQL_ROOT_PASSWORD=root987654
      - MYSQL_USER=s2ecom
      - MYSQL_PASSWORD=s2ecom654321
    ports:
      - "3308:3306"
    volumes:
      - "./data/mysql:/var/lib/mysql"
```

### Start MySql database
```bash
$ cd ~/s2ecom-order-service/env/local/mysql-3308/
$ docker-compose up -d

Creating network "mysql-3308_default" with the default driver
Creating mysql_s2ecom_3308 ... done
```

### Check MySql ready
```bash
$ docker logs -f mysql_s2ecom_3308

...
2020-08-11T04:05:24.148223Z 0 [Note] Skipping generation of RSA key pair as key files are present in data directory.
2020-08-11T04:05:24.148799Z 0 [Note] Server hostname (bind-address): '*'; port: 3306
2020-08-11T04:05:24.148921Z 0 [Note] IPv6 is available.
2020-08-11T04:05:24.148937Z 0 [Note]   - '::' resolves to '::';
2020-08-11T04:05:24.148964Z 0 [Note] Server socket created on IP: '::'.
2020-08-11T04:05:24.153140Z 0 [Warning] Insecure configuration for --pid-file: Location '/var/run/mysqld' in the path is accessible to all OS users. Consider choosing a different directory.
2020-08-11T04:05:24.164599Z 0 [Note] Event Scheduler: Loaded 0 events
2020-08-11T04:05:24.164831Z 0 [Note] mysqld: ready for connections.
Version: '5.7.31'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server (GPL)
```

## Edit the config file

Modify the following file
`file [git repo]/src/config.json`
```json
{
  "validTokens": [
    "fe1b83a0d33a467d8b38826ea361739b",
    "e673279b5a7d4f66adfef2fd3d6b7f09"
  ],
  "database": {
    "host": "localhost",
    "port": 3308,
    "username": "s2ecom",
    "password": "s2ecom654321",
    "databaseName": "s2ecom_order"
  },
  "paymentServiceEndpoint": "http://localhost:3030/",
  "paymentServiceClientSecret": "secret_a361739b",
  "autoUpdateOrderStatusCron": "*/50 * * * * *"
}
```


Param | Description
---------|----------
 `validTokens` | mockup an array of token data (token is one of required information in most APIs, token will be passed to `x-s2ecom-token` in every request header)
 `database` | MySql database connection info
 `paymentServiceEndpoint` | define the endpoint of Payment Service
 `paymentServiceClientSecret` | define the client secret key of Payment Service, Payment Service requires this secret key in most APIs, the value of secret key will be declare in Payment Service project
 `autoUpdateOrderStatusCron` | define cron expression for scan confirmed orders, default value is `*/50 * * * * *`, it means the job will be run **EVERY 50 SECONDS**.

## Start Order Service
### Start 
```bash
$ cd ~/s2ecom-order-service
$ npm install
$ npm run start:dev

...
Adding store-procedure ... success
[Nest] 15536   - 08/11/2020, 11:21:38 AM   [NestApplication] Nest application successfully started +213ms
Application is running on: http://[::1]:3010
```

### Verify
```bash
$ curl http://localhost:3010

Order service is running version=DEV_SNAPSHOT, SHA=CI_COMMIT_SHA
```

## API 

### Authentication
Most of APIs require token as a value of `x-s2ecom-token` in request header

Header | Value | Description
-------|-------|------------
x-se2ecom-token | fe1b83a0d33a467d8b38826ea361739b | token test 1
-- | fe1b83a0d33a467d8b38826ea361739b | token test 2

### Response on error sample
```json
HTTP status code = 403

{
    "statusCode": 403,
    "message": "Forbidden resource",
    "error": "Forbidden"
}
```   
   
   
# WORKING PROCESS

![Working Process Diagram!](/docs/working-process.png "Working Process Diagram")


# Submit order and payment

![Order Payment Sequence Diagram 1!](/docs/order-payment-sequence-diagram-1.png "Order Payment Sequence Diagram 1")


## A1 - Frontend submit an order

![A1!](/docs/A1.png "Submit an order")

### Order entity

|Param  | Description  |
|---------|---------|
|id | order id (in number) |
|code | order id (in text), for display on UI or use as reference code when call Payment Service API |
|amount | the amount of order - refer to data type `DECIMAL(18, 2)` |
|currencyCode | the currency code - i.e `EUR` or `USD` |
|orderStatus | status of order (see [OrderStatus enum](#orderstatus-enum) below) |
|orderStatusString | status of order (in text) for display |
|paymentStatus | status of Payment (see [PaymentStatus enum](#paymentstatus-enum) below) |
|paymentStatusString | status of Payment (in text) for display|
|createdAt | creation time (in UTC) |
|intentId | refer to `payment-intent` from Payment Service, each order need to link to an unique `payment-intent` |
|chargeToken | refer to `charge` from Payment Service, `charge-token` is the result of verify successfully enduser's payment info (i.e card number, card CVV, card expired date, 3DS verify, secret questions ...), `charge-token` will be used for confirm payment in final step |
|description | order description |

### OrderStatus enum

|Value  | Text  | Description  |
|---------|---------|---------|
|`1` | Created | Order has been created
|`2` | Confirmed | Order has successfull payment.
|`-1` | Cancelled | Order has been cancelled by enduser or when received failed payment.
|`3` | Delivered | When order changed to Confirmed status, after **X amount of seconds** (ie: x = 50 seconds), it will be changed toDelivered.

### PaymentStatus enum

|Value  | Text  | Description  |
|---------|---------|---------|
|`1` | Pending | Payment are pending to process
|`2` | Processing | Payment is processing
|`-1` | Failed | Payment failed
|`3` | Suceeded | Payment successful


## Submit order steps
(all steps must be execute in a transaction)
- Insert order data to `orders` table (with the default order status = Created, and payment status = Pending)
- Call Payment Service API to create new `Payment Intent`
- Link `payment-intent-id` into order
- Return data order for client

### Request
```bash
curl --location --request POST 'https://simon-s2ecom-order.vmo.group/orders/submit' \
--header 'x-s2ecom-token: fe1b83a0d33a467d8b38826ea361739b' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "description": "TXTR By Cantu Sleek Color Treated Hair + Curls Cleansing Oil Shampoo 16oz/473ml"
    }
}'
```

|Param  |Description |
|---------|---------|
|`item` | [Order entity](#order-entity) (required)|
|`item.amount` | order amount  (required)|
|`item.currencyCode` | currency code  (required)|
|`item.description` | Description (optional)|


### Response success
```json
{
    "item": {
        "id": 1596793199,
        "code": "ORD-1596793199",
        "amount": 11.95,
        "currencyCode": "EUR",
        "orderStatus": 1,
        "orderStatusString": "Created",
        "paymentStatus": 1,
        "paymentStatusString": "Pending",
        "createdAt": "2020-08-07T16:40:12.000Z",
        "customerId": 18,
        "intentId": "INTENT-1596798028",
        "chargeToken": null,
        "description": "TXTR By Cantu Sleek Color Treated Hair + Curls Cleansing Oil Shampoo 16oz/473ml"
    }
}
```

|Param  |Description |
|---------|---------|
|`item` | [Order entity](#order-entity)|

## A2 - Create payment intent
 
### Request
```bash
curl --location --request POST 'https://simon-s2ecom-payment.vmo.group/payments/create-intent' \
--header 'x-s2ecom-client-secret: secret_a361739b' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "referenceCode": "ORD-1596906020",
        "referenceNotes": "TXTR By Cantu Sleek Color Treated Hair + Curls Cleansing Oil Shampoo 16oz/473ml"
    }
}'
```

|Param  |Description |
|---------|---------|
|`item.amount` | Amount of order (required)|
|`item.currencyCode` | Currency code (required)|
|`item.referenceCode` | order id in text (required)|
|`item.referenceNotes` | notes of order if any (optional) |


### Response success
```json
{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "referenceCode": "ORD-1596906020",
        "referenceNotes": "TXTR By Cantu Sleek Color Treated Hair + Curls Cleansing Oil Shampoo 16oz/473ml",
        "id": 1597156648,
        "merchantId": "MERCHANT-2468",
        "intentId": "INTENT-1597156648",
        "chargeToken": null,
        "transactionStatus": 1,
        "createdAt": "2020-08-11T14:37:28.000Z",
        "updatedAt": "2020-08-11T14:37:28.112Z",
        "transactionStatusString": "Draft"
    }
}
```

## A3 - Enter payment info
 
![A3!](/docs/A3.png "Enter payment info")



## A4 - Submit payment
 
### Request
```bash
curl --location --request POST 'https://simon-s2ecom-payment.vmo.group/payments/submit' \
--header 'x-s2ecom-client-secret: secret_a361739b' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "referenceCode": "ORD-1596793199"
    },
    "paymentInfo": {
        "type": "visa",
        "data": "mlC3W6TDOhhL2FdgvdIC7sDv7G1Z7pCNzFLp0lwUwDPglyJu9LOnkBAf4vxSpQgQZltcz7LWwEquhdm5kSQIkQlZtfxtSTsmawq6gVH8SimlC3W6TDOhhL2FdgvdIC7sDv7G1Z7pCNzFLp0lgB9ACm8r5RZOBiN5ske9cBVjlVfgmQ9VpFzSwzLLODhCU7/2THg2iDrW3NGQZfz3SSWviwCe7G"
    }
}'
```

|Param  |Description |
|---------|---------|
|`item.amount` | Amount of order (required)|
|`item.currencyCode` | Currency code (required)|
|`item.intentId` | Id of `payment-intent` - this field has been returned to client after submit order (required)|
|`item.referenceCode` | Order id in text (required)|
|`paymentInfo` | the payment info data |
|`paymentInfo.type` | payment type i.e = `visa`|
|`paymentInfo.data` | encrypted sensitive data (i.e `card-number`, `card-expired`, `carrd-cvv`, ...)|

### Response success
```json
{
    "item": {
      "intentId": "INTENT-1596952807",
      "chargeToken": "CHARGE-f2f907b08f07443fbbdc425f04cdf76a"
    }
}
```

|Param  |Description |
|---------|---------|
|`item.intentId` | Id of `payment-intent-id` matched with request|
|`item.chargeToken` | This charge token will be use next step for confirm payment |


## A6 - Confirm payment

### Request

```bash
curl --location --request POST 'https://simon-s2ecom-order.vmo.group/orders/confirm-payment' \
--header 'x-s2ecom-token: fe1b83a0d33a467d8b38826ea361739b' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "id": 1596952807,
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "chargeToken": "CHARGE-f2f907b08f07443fbbdc425f04cdf76a"
    }
}'
```

|Param  |Description |
|---------|---------|
|`item.amount` | Amount of order (required) |
|`item.currencyCode` | Currency code (required) |
|`item.intentId` | Id of `payment-intent` get from order submit step (required)|
|`item.chargeToken` | The charge token issued by Payment Service from step above (required)|

### Response success
```json
{
    "item": {
        "id": 1596952807,
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "chargeToken": "CHARGE-f2f907b08f07443fbbdc425f04cdf76a",
        "orderStatus": 1,
        "paymentStatus": 2,
        "orderStatusString": "Created",
        "paymentStatusString": "Processing"

    },
    "chargeReceived": true
}
```


## A7 - Charge payment
 
### Request
```bash
curl --location --request POST 'https://simon-s2ecom-payment.vmo.group/payments/charge' \
--header 'x-s2ecom-client-secret: secret_a361739b' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "chargeToken": "CHARGE-f2f907b08f07443fbbdc425f04cdf76a",
    }
}'
```

|Param  |Description |
|---------|---------|
|`item.amount` | Amount of order (required)|
|`item.currencyCode` | Currency code (required)|
|`item.intentId` | Id of `payment-intent` get from order submit step (required)|
|`item.chargeToken` | The charge token issued by Payment Service from step above (required)|


### Response success
```json
{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "chargeToken": "CHARGE-f2f907b08f07443fbbdc425f04cdf76a"
    },
    "chargeReceived": true
}
```

|Param  |Description |
|---------|---------|
|`chargeReceived` | `true`: the payment is now processing, charge request has been send sucessfully. Otherwise this charge request is failed.|


## A8 - Trigger process flow
see [Process payment result flow](#process-payment-result-flow) below.

# Process payment result flow

![Process payment result flow!](/docs/order-payment-sequence-diagram-2.png "Process payment result flow")

## A9 - Process and get charge result

In this step we simulate the process of bank and the charge result could be **SUCCESSFUL** or **FAILED**
```bash
SIMULATION
we have 3 options for getting bank result, this is just for testing purpose only
 - random: the result will be random by checking current unix timestamp
 - sucess: return 'ChargeSucessful' only
 - failed: return 'ChargeFailed' only
```

## A10 - Notify Order Service

After get the charge result from step above, Payment Service will notify the Order Service via webhook

### Case 1: Payment charge successfully

### Request

```bash
curl --location --request POST 'https://simon-s2ecom-order.vmo.group/orders/webhook' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "referenceCode": "ORD-1596952807"
    },
    "paymentTransactionStatus": 2,
    "paymentTransactionStatusString": "ChargeSucessful"
}'
```

|Param  |Description |
|---------|---------|
|`item.amount` | Amount of order |
|`item.currencyCode` | Currency code |
|`item.intentId` | Id of `payment-intent` |
|`item.referenceCode` | The reference code that Order Service pass to Payment Service from step above|
|`paymentTransactionStatus` | The [PaymentTransaction status](#paymenttransaction-status)|
|`paymentTransactionStatusString` | The status of payment transaction in text |

### Response success
```json
{}
```

### PaymentTransaction status
|Value  | Text  | Description  |
|---------|---------|---------|
|`1` | Draft | The payment transaction has been created and waiting to process
|`2` | ChargeProcessing | Payment transaction are processing or being processed
|`3` | ChargeSuccessful | Payment transaction charge successfully
|`3` | ChargeFailed | Payment transaction failed


### Case 2 - Payment charge failed

### Request

```bash
curl --location --request POST 'https://simon-s2ecom-order.vmo.group/orders/webhook' \
--header 'Content-Type: application/json' \
--data '{
    "item": {
        "amount": 11.95,
        "currencyCode": "EUR",
        "intentId": "INTENT-1596798028",
        "referenceCode": "ORD-1596952807"
    },
    "paymentTransactionStatus": 3,
    "paymentTransactionStatusString": "ChargeFailed"
}'
```

### Response success
```json
{}
```


## A11 - Update order status

Base on the charge result, the order status and payment status will be updated
- if ChargeSuccessful then
    - change order status -> `Confirmed`
    - change payment status -> `Suceeded`

- if ChargeFailed
     - change order status -> `Cancelled`
     - change payment status -> `Failed`


# Fetch order status flow

![Fetch order status flow!](/docs/order-payment-sequence-diagram-3.png "Fetch order status flow")

## Fetch order status 

### Request

```bash
curl --location --request GET 'https://simon-s2ecom-order.vmo.group/orders/fetch?ids=1596957646,1596957677' \
--header 'x-s2ecom-token: fe1b83a0d33a467d8b38826ea361739b' \
--header 'Content-Type: application/json'
```

|Param  |Description |
|---------|---------|
|`ids` | List of order id, split by "`,`"|

### Response success
```json
{
    "pageNum": 1,
    "pageSize": 10,
    "totalRecords": 2,
    "items": [
        {
            "id": 1596957646,
            "orderStatus": 1,
            "paymentStatus": 1,
            "orderStatusString": "Created",
            "paymentStatusString": "Created"
        },
        {
            "id": 1596957677,
            "orderStatus": 1,
            "paymentStatus": 2,
            "orderStatusString": "Created",
            "paymentStatusString": "Processing"
        }
    ]
}
```