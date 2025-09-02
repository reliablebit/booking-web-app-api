# 🚀 Booking App API Documentation

**Base URL:** `http://your-domain.com/api`

## 🔐 Authentication
- **Type:** JWT (JSON Web Token)
- **Header:** `Authorization: Bearer {token}`
- **Token obtained from:** Login/Register endpoints

---

## 📋 API Endpoints Overview

### 🔓 **PUBLIC ROUTES** (No Authentication Required)

#### 1. **User Registration**
- **URL:** `POST /users/register`
- **Description:** Register a new user
- **Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "role": "user"
}
```
- **Validation Rules:**
  - `name`: required, string, max 255 chars
  - `email`: required, valid email, unique
  - `phone`: required, string, unique
  - `password`: required, min 6 chars
  - `role`: required, one of: "user", "merchant", "admin"

- **Success Response (200):**
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

- **Error Response (422):**
```json
{
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 6 characters."]
}
```

#### 2. **User Login**
- **URL:** `POST /users/login`
- **Description:** Login user and get JWT token
- **Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

- **Success Response (200):**
```json
{
    "message": "Login successful",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890"
    }
}
```

- **Error Response (401):**
```json
{
    "error": "Invalid credentials"
}
```

#### 3. **Merchant Registration**
- **URL:** `POST /merchant/register`
- **Description:** Register a new merchant
- **Request Body:**
```json
{
    "name": "Business Owner",
    "email": "merchant@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "company_name": "ABC Transport",
    "license_number": "LIC123456",
    "category": "bus",
    "address": "123 Business St"
}
```

- **Success Response (201):**
```json
{
    "status": "success",
    "message": "Merchant registered successfully",
    "user_id": 2,
    "merchant_id": 1,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 3600
}
```

---

### 🔒 **PROTECTED ROUTES** (Authentication Required)

#### 4. **Get Current User Profile**
- **URL:** `GET /users/me`
- **Headers:** `Authorization: Bearer {token}`
- **Description:** Get current authenticated user's profile
- **Response (200):**
```json
{
    "status": "success",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "role": "user"
    }
}
```

#### 5. **Logout**
- **URL:** `POST /users/logout`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
{
    "message": "Successfully logged out"
}
```

#### 6. **Check Availability**
- **URL:** `GET /availability/{listingId}`
- **Headers:** `Authorization: Bearer {token}`
- **Description:** Check real-time seat availability for a listing
- **Response (200):**
```json
{
    "listing_id": 1,
    "total_seats": 50,
    "confirmed": 25,
    "held": 5,
    "available": 20,
    "start_time": "2025-08-30T10:00:00Z",
    "location": "New York",
    "price": "25.00",
    "type": "bus"
}
```

---

### 👤 **USER ROUTES** (Role: user)

#### 7. **Search Listings**
- **URL:** `GET /search`
- **Headers:** `Authorization: Bearer {token}`
- **Query Parameters:**
  - `type`: "bus", "flight", "train" (optional)
  - `location`: string (optional)
  - `date`: YYYY-MM-DD (optional)
- **Example:** `GET /search?type=bus&location=New York&date=2025-08-30`
- **Response (200):**
```json
[
    {
        "id": 1,
        "title": "NYC to Boston Express",
        "type": "bus",
        "price": "25.00",
        "total_seats": 50,
        "available_seats": 20,
        "start_time": "2025-08-30T10:00:00Z",
        "location": "New York",
        "merchant": {
            "id": 1,
            "business_name": "ABC Transport",
            "category": "bus"
        }
    }
]
```

#### 8. **Create Booking (Hold Seat)**
- **URL:** `POST /bookings`
- **Headers:** `Authorization: Bearer {token}`
- **Description:** Hold a seat for 10 minutes
- **Request Body:**
```json
{
    "listing_id": 1,
    "seat_number": "A1"
}
```
- **Note:** `seat_number` is optional for free seating

- **Success Response (201):**
```json
{
    "message": "Seat held. Complete payment to confirm.",
    "hold_expires": "2025-08-27T10:10:00Z",
    "booking_id": 1,
    "booking_ref": "ABC123XYZ"
}
```

- **Error Response (409):**
```json
{
    "error": "Seat already taken or on hold"
}
```

#### 9. **Confirm Booking**
- **URL:** `POST /bookings/{id}/confirm`
- **Headers:** `Authorization: Bearer {token}`
- **Description:** Confirm payment and finalize booking
- **Response (200):**
```json
{
    "message": "Booking confirmed",
    "booking": {
        "id": 1,
        "booking_ref": "ABC123XYZ",
        "status": "confirmed",
        "seat_number": "A1",
        "listing": {
            "title": "NYC to Boston Express",
            "start_time": "2025-08-30T10:00:00Z",
            "price": "25.00"
        },
        "ticket": {
            "id": 1,
            "qr_code_path": "storage/qrcodes/1.svg",
            "issued_at": "2025-08-27T10:05:00Z"
        }
    }
}
```

#### 10. **Cancel Booking**
- **URL:** `POST /bookings/{id}/cancel`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
{
    "message": "Booking cancelled"
}
```

#### 11. **Get Booking Details**
- **URL:** `GET /bookings/{id}`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
{
    "id": 1,
    "booking_ref": "ABC123XYZ",
    "status": "confirmed",
    "seat_number": "A1",
    "listing": {
        "title": "NYC to Boston Express",
        "start_time": "2025-08-30T10:00:00Z",
        "price": "25.00",
        "location": "New York"
    },
    "ticket": {
        "id": 1,
        "qr_code_path": "storage/qrcodes/1.svg",
        "issued_at": "2025-08-27T10:05:00Z"
    }
}
```

#### 12. **Download Ticket QR Code**
- **URL:** `GET /bookings/{id}/ticket`
- **Headers:** `Authorization: Bearer {token}`
- **Description:** Download SVG QR code file
- **Response:** File download (SVG format)

---

### 🏪 **MERCHANT ROUTES** (Role: merchant)

#### 13. **Create Listing**
- **URL:** `POST /merchant/listings`
- **Headers:** `Authorization: Bearer {token}`
- **Request Body:**
```json
{
    "title": "NYC to Boston Express",
    "type": "bus",
    "price": 25.00,
    "total_seats": 50,
    "start_time": "2025-08-30T10:00:00Z",
    "location": "New York"
}
```

- **Validation Rules:**
  - `title`: required
  - `type`: required, one of: "bus", "flight", "train"
  - `price`: required, numeric
  - `total_seats`: required, integer, min 1
  - `start_time`: required, valid date
  - `location`: required

- **Response (200):**
```json
{
    "id": 1,
    "merchant_id": 1,
    "title": "NYC to Boston Express",
    "type": "bus",
    "price": "25.00",
    "total_seats": 50,
    "available_seats": 50,
    "start_time": "2025-08-30T10:00:00Z",
    "location": "New York"
}
```

#### 14. **Get Merchant Bookings**
- **URL:** `GET /merchant/bookings`
- **Headers:** `Authorization: Bearer {token}`
- **Description:** Get all bookings for merchant's listings
- **Response (200):**
```json
[
    {
        "id": 1,
        "booking_ref": "ABC123XYZ",
        "status": "confirmed",
        "seat_number": "A1",
        "user": {
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "+1234567890"
        },
        "ticket": {
            "qr_code_path": "storage/qrcodes/1.svg",
            "issued_at": "2025-08-27T10:05:00Z"
        }
    }
]
```

#### 15. **Get Merchant Stats**
- **URL:** `GET /merchant/stats`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
{
    "total_bookings": 150,
    "revenue": 3750.00
}
```

---

### 👑 **ADMIN ROUTES** (Role: admin)

#### 16. **Get All Users**
- **URL:** `GET /admin/users`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890"
    }
]
```

#### 17. **Get All Merchants**
- **URL:** `GET /admin/merchants`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
[
    {
        "id": 1,
        "business_name": "ABC Transport",
        "category": "bus",
        "status": "approved",
        "user": {
            "name": "Business Owner",
            "email": "merchant@example.com"
        }
    }
]
```

#### 18. **Approve/Reject Merchant**
- **URL:** `POST /admin/merchants/{id}/approve`
- **Headers:** `Authorization: Bearer {token}`
- **Request Body:**
```json
{
    "status": "approved"
}
```
- **Status values:** "approved", "rejected", "pending"

#### 19. **Get All Listings**
- **URL:** `GET /admin/listings`
- **Headers:** `Authorization: Bearer {token}`

#### 20. **Get All Bookings**
- **URL:** `GET /admin/bookings`
- **Headers:** `Authorization: Bearer {token}`

#### 21. **Get Analytics**
- **URL:** `GET /admin/analytics`
- **Headers:** `Authorization: Bearer {token}`
- **Response (200):**
```json
{
    "total_users": 1250,
    "total_merchants": 45,
    "total_listings": 180,
    "total_bookings": 3200,
    "total_revenue": 125000.00
}
```

---

## 🚨 **Common Error Responses**

### 401 Unauthorized
```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
    "error": "Forbidden"
}
```

### 404 Not Found
```json
{
    "message": "No query results for model [App\\Models\\Booking] 123"
}
```

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

---

## 📱 **Frontend Integration Tips**

### 1. **Authentication Flow**
1. User registers/logs in → Store JWT token
2. Include token in all protected requests
3. Handle token expiration → Redirect to login

### 2. **Booking Flow**
1. Search listings → Display results
2. User selects listing → Create booking (hold seat)
3. Payment process → Confirm booking
4. Show ticket with QR code

### 3. **Role-based UI**
- **User:** Search, book, view bookings
- **Merchant:** Create listings, view bookings, stats
- **Admin:** Manage users, merchants, analytics

### 4. **Real-time Updates**
- Use availability endpoint before booking
- Handle seat conflicts gracefully
- Show countdown for held seats (10 minutes)

---

## 🔧 **HTTP Status Codes Used**
- `200` - Success
- `201` - Created
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `409` - Conflict (seat taken)
- `422` - Validation Error

- 
## Known Issues & Limitations

### Critical Issues
1. **Payment Integration**: Uses test keys, no real Stripe configuration
2. **Route Duplication**: `additional_api_routes.php` has overlapping routes
3. **QR Verification**: Incomplete validation logic
4. **Fraud Detection**: Placeholder VPN/proxy checks

### Logic Issues
1. **Race Conditions**: Inconsistent use of database locks
2. **Hold Validation**: Incomplete hold expiration checks
3. **Seat Availability**: Mixed static/dynamic availability tracking
4. **Error Handling**: Inconsistent error responses

### Security Concerns
1. **No Rate Limiting**: Vulnerable to API abuse
2. **Input Validation**: Some endpoints lack comprehensive validation
3. **File Security**: QR code storage lacks proper validation

### Missing Features
1. **Email Notifications**: No booking confirmations
2. **Background Jobs**: No cleanup schedulers
3. **Audit Logging**: No activity tracking
4. **Real-time Updates**: No WebSocket support

---
