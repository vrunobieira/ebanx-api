# ebanx-api (take-home-test)

* Laravel framework 11.4.0
* php8.2.12 (Zend Engine v4.2.12)

# File map

* app > routes > api.php - Laravel route map

* app > Exceptions - Custom exceptions for event handlers and error tracking

* app > Http > Controllers - HTTP action handlers

* app > Http > Requests - Laravel form-requests to handle route parameter validations

* app > Services > Services - Isolated components to handle http actions and interfaces to 3rd party integrations

# Routes

* POST /api/account/reset

* POST /api/account/create
    - body: {id: string, amount: int}

* GET /api/account/:id/balance

* POST /api/account/:id/withdraw
    - body: {amount: int}

* POST /api/account/:id/transfer
    - body: {destination: string, amount: int}

* POST /api/account/:id/deposit
    - body: {amount: int}

# Test suite for https://ipkiss.pragmazero.com/

```
--
# Reset account state

POST /api/account/reset

200 OK


--
# Get balance for non-existing account

GET /api/account/1234/balance

404 ["The account ID 1234 is invalid or non-existent"]


--
# Create account with invalid parameters

POST /api/account/create {}

422 
{"message":"The id field is required. (and 1 more error)","errors":{"id":["The id field is required."],"amount":["The amount field is required."]}}


--
# Create new account - success

POST /api/account/create {"id": "100", "amount": 10}

200 {"id":"100","balance":10}


--
# Create another new account - success

POST /api/account/create {"id": "101", "amount": 15}

200 {"id": "101", "amount": 15}


--
# Create new account same ID - error

POST /api/account/create {"id": "101", "amount": 15}

404 ["The account 101 already exists and cannot be created"]


--
# Get balance existing account - success

GET /api/account/100/balance

200 {"id":"100","balance":10}


--
# Create deposit with invalid parameters

POST /api/account/900/deposit {}

422 
{"message":"The amount field is required.","errors":{"amount":["The amount field is required."]}}


--
# Create deposit for invalid account

POST /api/account/900/deposit {"amount": 15}

404 
["The account ID 900 is invalid or non-existent"]


--
# Create deposit for valid account

POST /api/account/100/deposit {"amount": 15}

200 {"id":"100","balance":25}


--
# Create withdrawal with invalid parameters

POST /api/account/900/withdraw {}

422 {"message":"The amount field is required.","errors":{"amount":["The amount field is required."]}}


--
# Create withdrawal with invalid account

POST /api/account/900/withdraw {"amount": 15}

404 ["The account ID 900 is invalid or non-existent"]


--
# Create withdrawal with insuficient balance

POST /api/account/100/withdraw {"amount": 200}

404 ["The account 100 does not have enough balance to make this withdrawal. Current balance: 25"]


--
# Create withdrawal - success

POST /api/account/100/withdraw {"amount": 15}

200 {"id":"100","balance":10,"amount_withdrawn":15}


--
# Create transfer to same account

POST /api/account/100/withdraw {"destination": "100", "amount": 15}

404 ["The origin account cannot be the same as the destination account when transferring balances"]


--
# Create transfer with insuficient balance

POST /api/account/100/withdraw {"destination": "101", "amount": 200}

404 ["The account 100 does not have enough balance to make this transfer. Current balance: 10"]


--
# Create transfer - success

POST /api/account/100/withdraw {"destination": "101", "amount": 10}

200 {"origin":{"id":"100","balance":0},"destination":{"id":"101","balance":25},"amount_transferred":10}

```

--
~ vrunobieira :D ~
--