## Checklist (requirements)
- User Panel: auth, search, bookings, tickets, OTP, payments
- Merchant Panel: merchant auth, listings CRUD, bookings for merchant, stats, availability
- Admin Panel: user/merchant/listing/booking listing, analytics, role-based access, fraud, exports
- Stats & Analytics: merchant/global stats, optimized/pre-aggregated queries
- Booking & Checkout Flow: availability, hold/lock, confirm, cancel, payment/refund

## Per-module status

### 1) User Panel (Customer Web)
- POST /api/users/register — Done (`AuthController::register`, `routes/api.php`)
- POST /api/users/login — Done (`AuthController::login`)
- GET /api/search (filters category/date/location) — Done (`ListingController::search`)
- POST /api/bookings — Done (hold/lock implemented: `BookingController::store`)
- GET /api/bookings/:id (ticket info) — Done (`BookingController::show`)
- Tasks:
  - User/Booking/Ticket schemas — Done (`app/Models/User.php`, `Booking.php`, `Ticket.php`)
  - OTP login — Not implemented
  - Token-based authentication (JWT) — Done (Tymon JWT + login/register)
  - Search algorithm with filters — Done
  - Generate booking reference + QR code — Done (`booking_ref` + QR generated in `BookingController::confirm`)
  - Store ticket metadata — Done (`Ticket` model: `qr_code_path`, `issued_at`)
  - Payment processing + refund — Not implemented

### 2) Merchant/Partner Panel
- POST /api/merchants/register — Done (`MerchantAuthController::register`)
- POST /api/listings — Done (`MerchantListingController::store` under `/merchant` prefix)
- PATCH /api/listings/:id — Not implemented (no update route/method found)
- GET /api/bookings/merchant/:id — Partially: authenticated merchant can access `/merchant/bookings` (`MerchantListingController::bookings`); no public/id-based endpoint
- GET /api/merchant/stats — Done (`MerchantListingController::stats`)
- Tasks:
  - Merchant schema — Done (`app/Models/Merchant.php`)
  - Listing schema (category-based) — Done (`Listing` model with `type`)
  - Availability + seat allocation logic — Done (locks, seat checks in `BookingController`, `BookingLock`, `AvailabilityController`)
  - Revenue calculation per merchant — Basic implementation done (sum bookings * price) but simplistic
  - Refund/cancellation handling — Partial: booking cancellation sets status and releases holds; payment refund orchestration not implemented

### 3) Admin Panel
- GET /api/admin/users — Done (`AdminController::users`)
- GET /api/admin/merchants — Done (`AdminController::merchants`)
- GET /api/admin/listings — Done (`AdminController::listings`)
- GET /api/admin/bookings — Done (`AdminController::bookings`)
- GET /api/admin/analytics — Done (`AdminController::analytics`)
- Tasks:
  - Role-based access control — Done (Spatie permissions + `role:` middleware)
  - Manage global listings & bookings — Done (admin endpoints present)
  - Fraud detection rules — Not implemented
  - Reporting & CSV exports — Not implemented

### 4) Stats & Analytics
- GET /api/stats/merchant/:id — Not implemented exactly; merchant-level stats exist for authenticated merchant (`/merchant/stats`), but there is no general `/stats/merchant/:id` endpoint
- GET /api/stats/global — Partially satisfied via `/admin/analytics` (simple counts & revenue)
- Tasks:
  - Aggregated queries — Basic aggregations implemented in controllers (counts/sums)
  - Optimized analytics queries — Not implemented (current code loads relations and sums in PHP)
  - Pre-aggregated JSON for charts — Not implemented

### 5) Booking & Checkout Flow
- GET /api/availability/:listingId — Done (`AvailabilityController::show`)
- POST /api/bookings — Done (HOLD implemented)
- POST /api/bookings/:id/confirm — Done (`BookingController::confirm`) but confirm does not validate an external payment
- POST /api/bookings/:id/cancel — Done (`BookingController::cancel`) (releases holds)
- Tasks:
  - Booking lock system — Done (`BookingLock` model + logic)
  - Seat/blocking logic with timeout — Done (10-minute TTL holds; cleanup of expired holds)
  - Confirmation after payment — Not implemented (no payment verification/webhook)
  - Cancellation/refund workflow — Partial: status change done; payment refund not implemented

## Summary — what's remaining (high priority)
1. Payment integration and refund flow
   - Add payment provider integration (checkout, webhooks) and link confirmation to actual successful payment
   - Implement refund endpoints and reconcile with booking cancellations
2. OTP login (if required by product spec)
3. PATCH /api/listings/:id (merchant listing updates)
4. Public/admin endpoints for merchant-level stats by id (`/stats/merchant/:id`) if required
5. Fraud detection rules & monitoring (basic rule engine, scores, or flags)
6. Reporting & CSV export endpoints for admin analytics
7. Analytics optimization — DB-level aggregations, materialized views, or scheduled pre-aggregations

## Suggested quick wins (prioritized)
- Wire a payment provider (Stripe/PayPal/Local gateway) and add webhook handler → makes confirm flow reliable.
- Add `PATCH /merchant/listings/{id}` to let merchants update listings (low-effort).
- Implement simple refund handler that maps booking cancellation → refund via gateway (medium effort).
- Add CSV export endpoint for admin (`/admin/export/bookings`) that streams CSV (low-medium effort).

## Notes & assumptions
- Auth: JWT + Spatie roles already present and enforced in routes.
- Revenue calculation currently multiplies booking count × listing price; it does not account for refunds, fees, or partial refunds.
- The project stores `available_seats` but availability is computed live using confirmed bookings + active holds.

---
Generated file: `BACKEND_RESPONSIBILITIES_STATUS.md` at project root.

If you want, I can convert this to a prioritized Jira-style task list, or add estimated story points/ETA for each remaining item. Which would you prefer?
