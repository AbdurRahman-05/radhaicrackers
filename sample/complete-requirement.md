# 📘 Cracker E-Commerce Portal with WhatsApp Integration

A Laravel 11 + Livewire 3 + Tailwind CSS (CDN-based) powered e-commerce platform for selling crackers, supporting OTP-based authentication via WhatsApp, PDF generation, manual payments, and admin management.

---

## 🎯 Objective

Build a complete mobile-responsive e-commerce web app with:
- OTP login via WhatsApp (no email/password)
- Product ordering with manual UPI payments
- PDF-based price list and order slips
- Admin features for stock, orders, payment verification
- WhatsApp message automation using deep links

---

## 🧩 Tech Stack

| Layer           | Tech Used                     |
|-----------------|-------------------------------|
| Backend         | Laravel 11.x                  |
| Frontend        | Blade + Livewire 3            |
| CSS             | Tailwind CSS via CDN          |
| Realtime UX     | Livewire components           |
| PDF Generation  | DomPDF / Snappy PDF           |
| Database        | MySQL or MariaDB              |
| Messaging       | WhatsApp Deep Links           |
| Architecture    | MVC                           |

---

## 🔐 Authentication Flow (OTP via WhatsApp)

1. User enters **Name** and **Phone Number**
2. App redirects user to WhatsApp with pre-filled OTP request link.
3. Laravel backend generates & stores OTP with expiry.
4. User enters OTP on the site → validated.
5. Access granted to order, price list, etc.

---

## 🖥️ Frontend Pages

- `/` – Home Page  
- `/about` – About Us  
- `/price-list` – Price List (PDF Download after login)  
- `/order` – Order Now Page  
- `/payment-options` – Manual Payment Info  
- `/contact` – Contact Details  
- `/track-order` – Check order status (OTP required)  

---

## 🛒 User Features

- OTP-based login (via WhatsApp link)
- View and download latest price list PDF
- Select crackers/products with quantity
- Submit order → WhatsApp summary sent
- Enter UPI ID & Transaction ID (Manual Payment)
- Dashboard to view:
  - All orders
  - Order status (Pending, Confirmed, Dispatched, Completed)
  - Payment status
  - Download confirmation slips

---

## 🛠️ Admin Features

- Admin login with username/password
- View dashboards with KPIs:
  - Total Orders
  - Pending, Completed, Dispatched count
- Manage orders:
  - View, update status, regenerate slips
  - Trigger WhatsApp resend
- Manage payments:
  - Review UPI/Txn IDs
  - Verify manually
- Stock management:
  - Add/edit stock items
  - Auto-release 10 items every 10 minutes
  - Auto-expire unused stock in 10 mins
  - Second batch trigger available
- View order logs, stock logs

---

## 📄 PDF Generation

- Price List PDF (`price-list.pdf`) – downloadable after OTP login
- Order Confirmation Slip (`order-XXXX.pdf`) with:
  - Customer Name
  - Phone Number
  - Ordered Items & Total
  - Downloadable

---

## 🔄 WhatsApp Integration

| Feature              | Method                     |
|----------------------|----------------------------|
| OTP Request          | Deep Link (`wa.me`)        |
| Order Summary        | WhatsApp message           |
| Admin Trigger        | Manual message link        |
| Automation Optional  | Interakt / Twilio API      |

---

## 💰 Payment System

- No online payment gateway
- Manual payment via UPI
- Users input UPI ID and Transaction ID
- Admin verifies and marks order as paid

---

## 🧬 Database Schema (Conceptual)

```
users
- id, name, phone, otp, otp_expires_at

orders
- id, user_id, total, status, created_at

order_items
- id, order_id, product_name, quantity

order_logs
- id, order_id, status, timestamp

stocks
- id, item_name, quantity, created_at, expires_at

payments
- id, order_id, upi_id, txn_id, verified_at
```

---

## 📁 Folder Structure (Logical Overview)

```
app/
├── Http/Controllers/
│   ├── OTPController.php
│   ├── OrderController.php
│   ├── AdminController.php
│   └── PDFController.php
├── Livewire/
│   ├── Auth/
│   ├── Pages/
│   ├── User/
│   └── Admin/
├── Models/
│   ├── User.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── OrderLog.php
│   ├── Stock.php
│   └── Payment.php
├── Services/
│   ├── OTPService.php
│   ├── WhatsAppService.php
│   └── PDFService.php

resources/views/
├── layouts/
├── livewire/
│   ├── auth/
│   ├── pages/
│   ├── user/
│   └── admin/
├── pdf/
├── components/

routes/
├── web.php
├── auth.php
├── admin.php

storage/app/public/pdfs/
```

---

## 🎁 Deliverables

- Laravel Livewire 3 Application
- Mobile-first Tailwind UI via CDN
- OTP login via WhatsApp deep link
- Order + Stock management backend
- WhatsApp order confirmation
- UPI-based manual payment system
- PDF price list & confirmation slips
- Admin dashboard and logs

---

## 🚀 Future Enhancements

- SMS OTP fallback
- Livewire charts for admin analytics
- PWA features for installability
- WhatsApp API integration with webhook auto-replies
- CSV/PDF report exports for admin