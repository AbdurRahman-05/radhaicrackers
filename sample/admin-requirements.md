
# 📋 Admin Requirements – Cracker E-Commerce Portal

This document outlines all necessary features and modules for the Admin Panel of the Cracker E-Commerce Platform built with Laravel 11 + Livewire 3 + Tailwind CSS (CDN only).

---

## 🎯 Objective

To provide a powerful and mobile-responsive Admin Dashboard for managing:
- User orders
- Manual UPI payments
- OTP-based logins
- Stock release & expiry logic
- WhatsApp message triggers
- Price list PDFs
- Logs and reports

---

## 🔐 Admin Authentication

- Secure login using:
  - **Username / Password**
- Protected admin routes with middleware
- Password change feature
- Future-proof for role-based permissions

---

## 📊 Dashboard Overview

- Total Users
- Total Orders
- Verified Payments
- Stock Items in Hand
- Today’s:
  - New Orders
  - Payments
  - WhatsApp OTP clicks
- Recent Activity (Last 5 events)

---

## 🛒 Order Management

- View all orders with filters:
  - Status: Pending / Confirmed / Dispatched / Completed
  - Payment Status: Paid / Not Paid
- View order details (products, quantities, user info)
- Update order status
- Regenerate confirmation PDF
- WhatsApp summary message trigger
- Export orders to PDF/CSV

---

## 💸 Payment Management

- Manual payment review:
  - View submitted UPI ID & Transaction ID
  - Mark as verified
- Filter:
  - Verified / Unverified
  - Date range
- Payment logs

---

## 📦 Stock Management

- Add/Edit/Delete stock items
  - Name, quantity
- Auto-release:
  - Release 10 units every 10 minutes
- Auto-expire:
  - Reclaimed if not ordered within 10 minutes
- Manual trigger for second batch release
- View full stock logs (release/expire/activity)

---

## 👥 User Management

- View all users:
  - Name, Phone, Last OTP Verified
- View all orders per user
- Block or deactivate users (if abuse is detected)

---

## 📄 PDF Manager

- Upload or replace Price List PDF (`price-list.pdf`)
  - Show last uploaded timestamp
- Download generated Order Confirmation PDFs
- Auto-generate Order Slips on order updates
- Export PDF reports for:
  - Orders
  - Payments
  - Stock

---

## 💬 WhatsApp Messaging

- Manual trigger buttons for:
  - OTP deep link
  - Order summary
  - Payment reminders
  - Dispatch notifications
- Dynamic WhatsApp message templates with variables:
  - `{name}`, `{otp}`, `{order_id}`, `{amount}`
- Copy-ready WhatsApp deep links
- Optional future integration:
  - Twilio / Interakt / Gupshup API

---

## 📝 Content Management

- Update content for static pages:
  - About Us
  - Payment Options
  - Contact Details
- Tailored WYSIWYG (Trix or textarea)
- Live preview

---

## ⚙️ Settings

- Business name
- Admin email/contact info
- UPI ID for manual payments
- Configure:
  - OTP expiry time (default: 5 mins)
  - Auto stock release (interval & quantity)
  - Auto stock expiry (default: 10 mins)

---

## 📚 Logs

- **Order Logs**:
  - Status change history
  - Timestamp + Admin responsible
- **Payment Logs**:
  - Marked as verified
  - TXN mismatch or rejected
- **Stock Logs**:
  - Released/Expired/Reset
- **WhatsApp Logs** (if enabled):
  - Clicks on OTP/order links

---

## 🔐 Optional: Role-Based Access

To support a multi-admin team:
- Super Admin – full access
- Order Manager – orders & payments only
- Stock Manager – stock only
- Content Manager – static pages + PDFs only

---

## 📂 Suggested Admin Panel Livewire Components

```
Livewire\Admin\
├── Dashboard.php
├── Orders.php
├── OrderDetails.php
├── Payments.php
├── Stocks.php
├── StockLogs.php
├── Users.php
├── ContentPages.php
├── PdfManager.php
├── WhatsAppLinks.php
└── Settings.php
```

Each component should have a corresponding Blade file in `resources/views/livewire/admin/`.

---

## 🧪 Testing Guidelines

- OTP deep link must correctly generate message to WhatsApp
- PDF links must generate/download without cache issues
- Auto-release must respect timer settings
- Admin changes should reflect without page reload (Livewire-powered)
- Stock should never go negative

---

## 🧩 Future Enhancements

- PWA Support for Admin Panel
- Live charts with Livewire + Laravel Chart.js
- OTP via SMS fallback
- WhatsApp Webhook Integration
- Audit logs for admin activity

---

**Last Updated:** {{ date('Y-m-d') }}
