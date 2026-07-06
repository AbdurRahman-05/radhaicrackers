# 📦 Cracker E-Commerce Portal with WhatsApp Integration

---

## 🧩 Tech Stack Overview

- **Backend**: Laravel 12.x
- **Realtime Components**: Laravel Livewire
- **Styling**: Tailwind CSS via CDN (No Node/NPM)
- **Database**: MySQL or MariaDB
- **Authentication**: OTP via WhatsApp (Deep Link)
- **Messaging**: WhatsApp Deep Links or API
- **PDF Generation**: Laravel Dompdf/Snappy
- **Architecture**: MVC

---

## 🖥️ Frontend Pages & Features

Mobile-friendly pages:

- 🏠 Home  
- 📄 About Us  
- 💰 Price List – 2025  
- 🛒 Order Now  
- 💳 Payment Options  
- 📞 Contact Us  
- 🚚 Track Order  

Each page includes navigation, Tailwind styling, and responsive layout.

---

## 🔐 User Flow

### 1. OTP Login via WhatsApp
- User enters name & phone
- Redirect to WhatsApp with pre-filled OTP request message
- Server stores generated OTP (with expiry)
- User enters OTP on site → verification
- Access granted to protected content

### 2. Price List Access
- Only accessible **after OTP verification**
- Price List shown as **viewable/downloadable PDF**

### 3. Order Placement
- User selects products and quantities
- Order saved to backend
- Summary PDF generated
- WhatsApp message triggered (order summary)
- No online payment — user must **manually enter** payment details

---

## 📦 Order Features (User Dashboard)

- View all past/current orders
- View order status: `pending`, `confirmed`, `dispatched`, `completed`
- Download order confirmation slips (PDF)
- View payment status
- Track individual orders

---

## 🛠️ Admin Features

### Admin Dashboard:
- Secure login (username + password)
- Order KPIs: total, pending, completed, dispatched
- View all orders with customer details
- Update order status (supports **partial dispatch**)
- Resend WhatsApp messages manually
- Regenerate confirmation slips (PDFs)
- View **order logs** and **stock logs**

---

## 📦 Stock Management (Admin)

- Add/edit stock entries
- Release stock in batches of 10 (auto every 10 minutes)
- Expire unused stock after 10 minutes
- Option to **send second batch**
- View all logs with timestamps

---

## 💰 Payment Options

- No payment gateway integration
- User enters:
  - UPI ID
  - Transaction ID
- Admin manually verifies payment
- Payment status reflected in user dashboard

---

## 🧾 PDF Generation

- 📄 **Price List PDF**
  - Static or dynamic content
  - Available post-login

- 📑 **Order Confirmation Slip**
  - Contains: customer name, phone, items, totals
  - Stored in `storage/app/public/pdfs`
  - Downloadable from user dashboard

---

## 🔄 WhatsApp Integration

### Used For:
- OTP sending
- Order summary messages
- Admin-triggered manual messages

### Method:
- Deep Link (`https://wa.me/91XXXXXXXXXX?text=...`)
- Optional future upgrade: API via Twilio, Interakt, etc.

---

## 🧬 Database Structure (Conceptual)

```
users
- id, name, phone, otp, otp_expiration

orders
- id, user_id, total, status, created_at

order_items
- id, order_id, product_name, quantity

order_logs
- id, order_id, status, changed_at

stocks
- id, item_name, quantity, created_at, expires_at

payments
- id, order_id, upi_id, txn_id, verified_at
```

---

## 🗂️ Folder Structure (Logical Overview)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── OTPController.php
│   │   ├── OrderController.php
│   │   ├── AdminController.php
│   │   └── PDFController.php
│   └── Livewire/
│       ├── Auth/
│       │   ├── LoginForm.php
│       │   └── OtpVerification.php
│       ├── PublicPages/
│       │   ├── HomePage.php
│       │   ├── AboutPage.php
│       │   ├── PriceListPage.php
│       │   ├── OrderNowPage.php
│       │   ├── ContactPage.php
│       │   ├── PaymentOptions.php
│       │   └── TrackOrder.php
│       ├── User/
│       │   ├── Dashboard.php
│       │   ├── Orders.php
│       │   └── ManualPayment.php
│       └── Admin/
│           ├── Dashboard.php
│           ├── OrdersManager.php
│           ├── StockManager.php
│           ├── PaymentVerifier.php
│           └── WhatsAppTrigger.php

app/Models/
├── User.php
├── Order.php
├── OrderItem.php
├── OrderLog.php
├── Stock.php
└── Payment.php

app/Services/
├── OTPService.php
├── WhatsAppService.php
└── PDFService.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   └── admin.blade.php
├── livewire/
│   ├── auth/
│   ├── public-pages/
│   ├── user/
│   └── admin/
├── pdf/
│   ├── price-list.blade.php
│   └── order-confirmation.blade.php
└── components/
    └── nav.blade.php

routes/
├── web.php
├── auth.php
└── admin.php

storage/
└── app/
    └── public/
        └── pdfs/
            ├── price-list/
            └── orders/
```

---

## 📤 Deliverables

- ✅ Laravel + Livewire functional application
- ✅ Mobile-first UI using Tailwind CDN
- ✅ OTP-based login via WhatsApp
- ✅ Order + stock management system
- ✅ WhatsApp message triggering (auto/manual)
- ✅ PDF generation (price list + confirmation)
- ✅ Manual UPI payment entry + admin approval
- ✅ Clean MVC + Livewire component architecture

---

## 🧠 Future Enhancements (Optional)

- PWA support for offline use
- SMS OTP fallback method
- Dashboard graphs using Chart.js or Livewire charts
- Scheduled WhatsApp reminder via jobs/queue
- Export reports (PDF/CSV)