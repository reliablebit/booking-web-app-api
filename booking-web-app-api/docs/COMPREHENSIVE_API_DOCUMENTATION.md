# COMPREHENSIVE API DOCUMENTATION

## Table of Contents
1. [Authentication](#authentication)
2. [OTP Authentication](#otp-authentication) 
3. [Search & Discovery](#search--discovery)
4. [Availability Management](#availability-management)
5. [Enhanced Booking System](#enhanced-booking-system)
6. [Cancellation System](#cancellation-system)
7. [Merchant Statistics](#merchant-statistics)
8. [Reporting & Analytics](#reporting--analytics)
9. [Admin Operations](#admin-operations)
10. [Error Handling](#error-handling)
11. [Security & Rate Limiting](#security--rate-limiting)

---

## Authentication

### Standard JWT Authentication
All protected endpoints require JWT token in Authorization header:
```
Authorization: Bearer <your-jwt-token>
```

### Base URLs
- **Production:** `https://your-app.com/api`
- **Development:** `http://localhost:8000/api`

---

## OTP Authentication

### Send OTP
**Endpoint:** `POST /api/otp/send`

**Request:**
```json
{
    "phone": "+1234567890"
}
```

**Response:**
```json
{
    "message": "OTP sent successfully",
    "otp": "123456",
    "expires_in": 300
}
```

### Verify OTP
**Endpoint:** `POST /api/otp/verify`

**Request:**
```json
{
    "phone": "+1234567890",
    "otp": "123456"
}
```

**Response:**
```json
{
    "message": "OTP verified successfully",
    "user": {
        "id": 1,
        "phone": "+1234567890",
        "verified": true
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

## Search & Discovery

### Advanced Search
**Endpoint:** `GET /api/search`

**Query Parameters:**
- `category` (optional): bus, hotel, event, flight
- `location` (optional): Location string
- `date` (optional): YYYY-MM-DD format
- `min_price` (optional): Minimum price
- `max_price` (optional): Maximum price
- `available_seats` (optional): Minimum available seats
- `page` (optional): Page number
- `per_page` (optional): Items per page (max 100)

**Example Request:**
```
GET /api/search?category=bus&location=NYC&date=2025-12-25&min_price=50&max_price=200&page=1
```

**Response:**
```json
{
    "message": "Search results retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "NYC to Boston Express",
            "type": "bus",
            "price": "89.99",
            "total_seats": 50,
            "available_seats": 23,
            "start_time": "2025-12-25T10:00:00Z",
            "location": "NYC Port Authority",
            "merchant": {
                "business_name": "Express Bus Lines",
                "category": "bus"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 67
    }
}
```

### Popular Listings
**Endpoint:** `GET /api/search/popular`

### Featured Listings  
**Endpoint:** `GET /api/search/featured?category=bus`

---

## Availability Management

### Get Listing Availability
**Endpoint:** `GET /api/availability/{listingId}`

**Response:**
```json
{
    "message": "Availability retrieved successfully",
    "listing": {
        "id": 1,
        "title": "NYC to Boston Express",
        "type": "bus",
        "price": "89.99",
        "start_time": "2025-12-25T10:00:00Z",
        "location": "NYC Port Authority"
    },
    "availability": {
        "total_seats": 50,
        "available_count": 23,
        "booked_count": 25,
        "locked_count": 2,
        "available_seats": [1, 3, 5, 7, 9, 11, 13, 15],
        "seat_map": [
            {"seat_number": 1, "status": "available"},
            {"seat_number": 2, "status": "booked"},
            {"seat_number": 3, "status": "locked"}
        ]
    }
}
```

### Reserve Seats (Protected)
**Endpoint:** `POST /api/availability/{listingId}/reserve`
**Auth:** Required

**Request:**
```json
{
    "seat_numbers": [15, 16],
    "quantity": 2
}
```

**Response:**
```json
{
    "message": "Reserved 2 seat(s) successfully",
    "listing_id": 1,
    "reserved_count": 2,
    "results": [
        {
            "seat_number": 15,
            "success": true,
            "lock_id": 123,
            "expires_at": "2025-09-01T12:15:00Z"
        }
    ],
    "lock_timeout_minutes": 15
}
```

### Release Reservations (Protected)
**Endpoint:** `POST /api/availability/{listingId}/release`
**Auth:** Required

**Request:**
```json
{
    "lock_ids": [123, 124]
}
```

### Get User Reservations (Protected)
**Endpoint:** `GET /api/availability/user/reservations`
**Auth:** Required

---

## Enhanced Booking System

### Create Enhanced Booking (Protected)
**Endpoint:** `POST /api/bookings/enhanced`
**Auth:** Required

**Request:**
```json
{
    "listing_id": 1,
    "seat_number": 15,
    "lock_id": 123,
    "payment_method_id": "pm_1234567890"
}
```

**Response:**
```json
{
    "message": "Booking created successfully",
    "booking": {
        "id": 1,
        "user_id": 1,
        "listing_id": 1,
        "status": "pending",
        "seat_number": "15",
        "booking_ref": "ABC123XYZ",
        "payment_intent_id": "pi_1234567890",
        "payment_status": "pending"
    },
    "payment": {
        "client_secret": "pi_1234567890_secret_...",
        "payment_intent_id": "pi_1234567890"
    },
    "qr_code": {
        "qr_code_path": "qr-codes/booking-ABC123XYZ.png",
        "qr_code_url": "https://your-app.com/storage/qr-codes/booking-ABC123XYZ.png"
    },
    "fraud_check": {
        "risk_level": "low",
        "risk_score": 8
    }
}
```

### Confirm Payment (Protected)
**Endpoint:** `POST /api/bookings/{bookingId}/confirm-payment`
**Auth:** Required

**Request:**
```json
{
    "payment_method_id": "pm_1234567890"
}
```

### Get Ticket Information (Protected)
**Endpoint:** `GET /api/bookings/{bookingId}/ticket-info`
**Auth:** Required

**Response:**
```json
{
    "message": "Booking retrieved successfully",
    "data": {
        "booking": {
            "id": 1,
            "status": "confirmed",
            "booking_ref": "ABC123XYZ",
            "seat_number": "15"
        },
        "ticket_info": {
            "ticket_number": "TKT-XYZ12345",
            "qr_code_url": "https://your-app.com/storage/qr-codes/ticket-TKT-XYZ12345.png",
            "event_details": {
                "title": "NYC to Boston Express",
                "location": "NYC Port Authority",
                "start_time": "2025-12-25T10:00:00Z",
                "seat_number": "15"
            }
        }
    }
}
```

### QR Code Verification
**Endpoint:** `POST /api/bookings/verify-qr`

**Request:**
```json
{
    "qr_data": "{\"ticket_id\":123,\"booking_ref\":\"ABC123XYZ\"}"
}
```

---

## Cancellation System

### Cancel Booking (Protected)
**Endpoint:** `POST /api/bookings/{bookingId}/cancel`
**Auth:** Required

**Request:**
```json
{
    "reason": "Plans changed",
    "refund_amount": 75.50
}
```

**Response:**
```json
{
    "message": "Booking cancelled successfully",
    "booking": {
        "id": 1,
        "status": "cancelled",
        "cancellation_reason": "Plans changed",
        "cancelled_at": "2025-09-01T12:00:00Z"
    },
    "refund_amount": 75.50,
    "refund_status": "processed"
}
```

### Get Cancellation Policy
**Endpoint:** `GET /api/bookings/{listingId}/cancellation-policy`

### Bulk Cancel (Admin Only)
**Endpoint:** `POST /api/bookings/bulk-cancel`
**Auth:** Admin required

**Request:**
```json
{
    "booking_ids": [1, 2, 3, 4, 5],
    "reason": "Event cancelled due to weather"
}
```

---

## Merchant Statistics

### Get Merchant Statistics (Protected)
**Endpoint:** `GET /api/stats/merchant/{merchantId}?days=30`
**Auth:** Required

**Response:**
```json
{
    "message": "Merchant statistics retrieved successfully",
    "period_days": 30,
    "data": {
        "merchant_info": {
            "id": 1,
            "business_name": "Express Bus Lines",
            "category": "bus",
            "status": "active"
        },
        "listings": {
            "total": 15,
            "new_this_period": 3,
            "active": 12,
            "expired": 3,
            "by_type": {
                "bus": 10,
                "event": 5
            },
            "average_price": 89.50
        },
        "bookings": {
            "total": 450,
            "new_this_period": 87,
            "by_status": {
                "confirmed": 380,
                "pending": 25,
                "cancelled": 45
            },
            "conversion_rate": 84.44,
            "daily_trends": {
                "2025-08-01": 12,
                "2025-08-02": 18,
                "2025-08-03": 15
            }
        },
        "revenue": {
            "total": 40275.50,
            "this_period": 7783.25,
            "average_booking_value": 89.50,
            "daily_trends": {
                "2025-08-01": 1074.00,
                "2025-08-02": 1611.00
            },
            "by_type": {
                "bus": 35000.00,
                "event": 5275.50
            }
        },
        "performance": {
            "popular_listings": [
                {
                    "id": 1,
                    "title": "NYC to Boston Express",
                    "booking_count": 45,
                    "revenue": 4027.50
                }
            ],
            "average_occupancy_rate": 76.8
        }
    }
}
```

### Global Statistics (Admin Only)
**Endpoint:** `GET /api/stats/global?days=30`
**Auth:** Admin required

---

## Reporting & Analytics

### Export Users (Admin Only)
**Endpoint:** `GET /api/reports/users/export?role=user&date_from=2025-01-01&date_to=2025-12-31`
**Auth:** Admin required
**Response:** CSV file download

### Export Bookings (Admin Only)
**Endpoint:** `GET /api/reports/bookings/export?status=confirmed&merchant_id=1`
**Auth:** Admin required
**Response:** CSV file download

### Revenue Report
**Endpoint:** `GET /api/reports/revenue?date_from=2025-01-01&date_to=2025-12-31&export=csv`
**Auth:** Admin required

**Response:**
```json
{
    "message": "Revenue report generated successfully",
    "period": {
        "from": "2025-01-01",
        "to": "2025-12-31"
    },
    "data": [
        {
            "business_name": "Express Bus Lines",
            "category": "bus",
            "total_bookings": 450,
            "total_revenue": "40275.50",
            "avg_booking_value": "89.50"
        }
    ]
}
```

### Analytics Summary
**Endpoint:** `GET /api/reports/analytics-summary?days=30&export=csv`
**Auth:** Admin required

---

## Admin Operations

### Manage Users (Admin Only)
**Endpoint:** `GET /api/admin/users`
**Auth:** Admin required

### Manage Merchants (Admin Only)
**Endpoint:** `GET /api/admin/merchants`
**Auth:** Admin required

### Approve Merchant (Admin Only)
**Endpoint:** `POST /api/admin/merchants/{merchantId}/approve`
**Auth:** Admin required

**Request:**
```json
{
    "status": "active"
}
```

### System Analytics (Admin Only)
**Endpoint:** `GET /api/admin/analytics`
**Auth:** Admin required

---

## Error Handling

### Standard Error Format
```json
{
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### HTTP Status Codes
- **200** - Success
- **201** - Created
- **400** - Bad Request
- **401** - Unauthorized
- **403** - Forbidden
- **404** - Not Found
- **422** - Validation Error
- **500** - Server Error

### Common Error Examples

**Validation Error (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

**Unauthorized (401):**
```json
{
    "error": "Unauthorized"
}
```

**Forbidden (403):**
```json
{
    "error": "Admin access required"
}
```

**Not Found (404):**
```json
{
    "message": "No query results for model [App\\Models\\Booking] 123"
}
```

---

## Security & Rate Limiting

### Required Headers
```http
Content-Type: application/json
Accept: application/json
Authorization: Bearer <token>
X-Requested-With: XMLHttpRequest
```

### Rate Limits
- **Authentication endpoints**: 5 requests per minute
- **Search endpoints**: 60 requests per minute
- **Booking endpoints**: 30 requests per minute
- **Admin endpoints**: 100 requests per minute

### Rate Limit Headers
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1693574400
```

### Security Features
- All inputs validated and sanitized
- SQL injection prevention via Eloquent ORM
- XSS protection on all outputs
- CSRF protection for state-changing operations
- PCI DSS compliant payment processing
- Fraud detection on all transactions

---

## Integration Examples

### Frontend Integration (JavaScript)
```javascript
// Search for listings
const searchListings = async (params) => {
    const response = await fetch('/api/search?' + new URLSearchParams(params));
    const data = await response.json();
    return data;
};

// Create booking with payment
const createBooking = async (bookingData, token) => {
    const response = await fetch('/api/bookings/enhanced', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(bookingData)
    });
    return await response.json();
};
```

### Mobile App Integration (React Native)
```javascript
// OTP authentication flow
const sendOTP = async (phone) => {
    return await fetch('/api/otp/send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone })
    });
};

const verifyOTP = async (phone, otp) => {
    return await fetch('/api/otp/verify', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ phone, otp })
    });
};
```

---

*This documentation covers all enhanced features and endpoints. For additional support or feature requests, please contact the development team.*
