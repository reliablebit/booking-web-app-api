# 🚀 ADVANCED FEATURES TESTING GUIDE

## Overview
This guide will teach you how to test and use the advanced features in your booking application: Reporting, QR Codes, Statistics, and more.

---

## 🔐 STEP 1: GET ADMIN ACCESS

First, let's get an admin token to access advanced features:

### Login as Admin
```bash
curl -X POST http://localhost:8000/api/users/login \
-H "Content-Type: application/json" \
-d '{
    "email": "admin@example.com",
    "password": "password123"
}'
```

**Expected Response:**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "Super Admin",
        "email": "admin@example.com"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

**💡 Save the token for next steps!**

---

## 📊 STEP 2: TESTING ADVANCED STATISTICS

### A. Global Statistics (Admin Only)
```bash
curl -X GET "http://localhost:8000/api/stats/global?days=30" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-H "Content-Type: application/json"
```

### B. Merchant Statistics 
```bash
curl -X GET "http://localhost:8000/api/stats/merchant/1?days=30" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-H "Content-Type: application/json"
```

**What You'll See:**
```json
{
    "message": "Merchant statistics retrieved successfully",
    "period_days": 30,
    "data": {
        "merchant_info": {
            "id": 1,
            "business_name": "Example Business",
            "category": "bus",
            "status": "active"
        },
        "listings": {
            "total": 5,
            "new_this_period": 2,
            "active": 4,
            "expired": 1,
            "by_type": {"bus": 3, "event": 2},
            "average_price": 89.50
        },
        "bookings": {
            "total": 25,
            "new_this_period": 8,
            "by_status": {
                "confirmed": 20,
                "pending": 3,
                "cancelled": 2
            },
            "conversion_rate": 80.0,
            "daily_trends": {
                "2025-09-01": 5,
                "2025-08-31": 3
            }
        },
        "revenue": {
            "total": 2237.50,
            "this_period": 715.00,
            "average_booking_value": 89.50,
            "daily_trends": {
                "2025-09-01": 447.50,
                "2025-08-31": 267.50
            }
        }
    }
}
```

---

## 📈 STEP 3: TESTING REPORTING & EXPORTS

### A. Export Users Report
```bash
curl -X GET "http://localhost:8000/api/reports/users/export?role=user&date_from=2025-01-01" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-o users_report.csv
```

### B. Export Bookings Report
```bash
curl -X GET "http://localhost:8000/api/reports/bookings/export?status=confirmed" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-o bookings_report.csv
```

### C. Revenue Report
```bash
curl -X GET "http://localhost:8000/api/reports/revenue?date_from=2025-01-01&date_to=2025-12-31" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-H "Content-Type: application/json"
```

**Revenue Report Response:**
```json
{
    "message": "Revenue report generated successfully",
    "period": {
        "from": "2025-01-01",
        "to": "2025-12-31"
    },
    "data": [
        {
            "business_name": "City Bus Services",
            "category": "bus",
            "total_bookings": 15,
            "total_revenue": "1340.25",
            "avg_booking_value": "89.35"
        },
        {
            "business_name": "Grand Hotel",
            "category": "hotel",
            "total_bookings": 8,
            "total_revenue": "1599.92",
            "avg_booking_value": "199.99"
        }
    ]
}
```

### D. Analytics Summary
```bash
curl -X GET "http://localhost:8000/api/reports/analytics-summary?days=30" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-H "Content-Type: application/json"
```

---

## 🎫 STEP 4: TESTING QR CODE GENERATION

### A. Create a Booking (to get QR code)
First, get a user token:
```bash
# Get any user from the seeded data
curl -X POST http://localhost:8000/api/users/login \
-H "Content-Type: application/json" \
-d '{
    "email": "john.doe@example.com",
    "password": "12345678"
}'
```

### B. Create Enhanced Booking with QR
```bash
curl -X POST http://localhost:8000/api/bookings/enhanced \
-H "Authorization: Bearer USER_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "listing_id": 1,
    "seat_number": "15",
    "payment_method_id": "pm_test_123"
}'
```

**Expected Response with QR Code:**
```json
{
    "message": "Booking created successfully",
    "booking": {
        "id": 1,
        "booking_ref": "ABC123XYZ",
        "status": "pending"
    },
    "qr_code": {
        "qr_code_path": "qr-codes/booking-ABC123XYZ.png",
        "qr_code_url": "http://localhost:8000/storage/qr-codes/booking-ABC123XYZ.png"
    },
    "fraud_check": {
        "risk_level": "low",
        "risk_score": 8
    }
}
```

### C. Verify QR Code
```bash
curl -X POST http://localhost:8000/api/bookings/verify-qr \
-H "Content-Type: application/json" \
-d '{
    "qr_data": "{\"booking_ref\":\"ABC123XYZ\",\"user_id\":1,\"listing_id\":1}"
}'
```

---

## 🔍 STEP 5: TESTING ADVANCED SEARCH

### A. Basic Search
```bash
curl -X GET "http://localhost:8000/api/search?category=bus&location=City&min_price=20&max_price=100"
```

### B. Popular Listings
```bash
curl -X GET "http://localhost:8000/api/search/popular"
```

### C. Featured Listings
```bash
curl -X GET "http://localhost:8000/api/search/featured?category=event"
```

**Search Response Example:**
```json
{
    "message": "Search results retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Express Bus Downtown",
            "type": "bus",
            "price": "25.50",
            "total_seats": 45,
            "available_seats": 23,
            "start_time": "2025-09-15T08:00:00Z",
            "location": "Central Station",
            "merchant": {
                "business_name": "City Bus Services",
                "category": "bus"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 42
    }
}
```

---

## 🎯 STEP 6: TESTING SEAT AVAILABILITY SYSTEM

### A. Check Availability
```bash
curl -X GET "http://localhost:8000/api/availability/1"
```

### B. Reserve Seats (Requires Auth)
```bash
curl -X POST "http://localhost:8000/api/availability/1/reserve" \
-H "Authorization: Bearer USER_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "seat_numbers": [15, 16],
    "quantity": 2
}'
```

**Reservation Response:**
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

### C. Release Reservations
```bash
curl -X POST "http://localhost:8000/api/availability/1/release" \
-H "Authorization: Bearer USER_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "lock_ids": [123, 124]
}'
```

---

## 🚫 STEP 7: TESTING BOOKING CANCELLATION

### A. Cancel a Booking
```bash
curl -X POST "http://localhost:8000/api/bookings/1/cancel" \
-H "Authorization: Bearer USER_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "reason": "Plans changed",
    "refund_amount": 75.50
}'
```

### B. Get Cancellation Policy
```bash
curl -X GET "http://localhost:8000/api/bookings/1/cancellation-policy"
```

### C. Bulk Cancel (Admin Only)
```bash
curl -X POST "http://localhost:8000/api/bookings/bulk-cancel" \
-H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
-H "Content-Type: application/json" \
-d '{
    "booking_ids": [1, 2, 3],
    "reason": "Event cancelled due to weather"
}'
```

---

## 📱 STEP 8: TESTING OTP AUTHENTICATION

### A. Send OTP
```bash
curl -X POST "http://localhost:8000/api/otp/send" \
-H "Content-Type: application/json" \
-d '{
    "phone": "+1234567890"
}'
```

### B. Verify OTP
```bash
curl -X POST "http://localhost:8000/api/otp/verify" \
-H "Content-Type: application/json" \
-d '{
    "phone": "+1234567890",
    "otp": "123456"
}'
```

---

## 🧪 TESTING WORKFLOW EXAMPLES

### Complete Booking Flow with Advanced Features:
```bash
# 1. Search for listings
curl -X GET "http://localhost:8000/api/search?category=bus"

# 2. Check availability
curl -X GET "http://localhost:8000/api/availability/1"

# 3. Reserve seats
curl -X POST "http://localhost:8000/api/availability/1/reserve" \
-H "Authorization: Bearer USER_TOKEN" \
-d '{"seat_numbers": [15], "quantity": 1}'

# 4. Create enhanced booking
curl -X POST "http://localhost:8000/api/bookings/enhanced" \
-H "Authorization: Bearer USER_TOKEN" \
-d '{"listing_id": 1, "seat_number": "15", "lock_id": 123}'

# 5. Get booking with QR code
curl -X GET "http://localhost:8000/api/bookings/1/ticket-info" \
-H "Authorization: Bearer USER_TOKEN"
```

### Admin Analytics Flow:
```bash
# 1. Get global stats
curl -X GET "http://localhost:8000/api/stats/global" \
-H "Authorization: Bearer ADMIN_TOKEN"

# 2. Export revenue report
curl -X GET "http://localhost:8000/api/reports/revenue?export=csv" \
-H "Authorization: Bearer ADMIN_TOKEN"

# 3. Get analytics summary
curl -X GET "http://localhost:8000/api/reports/analytics-summary" \
-H "Authorization: Bearer ADMIN_TOKEN"
```

---

## 🎯 KEY FEATURES EXPLAINED

### 📊 **Statistics System:**
- Real-time merchant performance metrics
- Revenue tracking with trends
- Booking conversion rates
- Occupancy rates and seat utilization

### 📈 **Reporting System:**
- CSV/Excel exports for all data
- Filtered reports by date ranges
- Revenue analysis by merchant/category
- User activity reports

### 🎫 **QR Code System:**
- Automatic QR generation for bookings
- Secure QR data with timestamps
- QR verification for ticket validation
- Storage in public/storage folder

### 🔒 **Seat Management:**
- 15-minute seat reservations
- Real-time availability tracking
- Seat locking during booking process
- Automatic lock release

### 📱 **OTP Authentication:**
- SMS-based verification (mock in development)
- Phone number authentication
- Temporary OTP codes
- Enhanced security layer

---

## 🚀 NEXT STEPS

1. **Start with Basic Features** - Use core booking system first
2. **Add Statistics** - Enable merchant dashboards
3. **Implement QR Codes** - For ticket validation
4. **Add Reporting** - For business intelligence
5. **Enable OTP** - For enhanced security

Your advanced features are ready to use! 🎉
