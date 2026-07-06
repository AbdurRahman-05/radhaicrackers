# 📦 Laravel 11 + Livewire 3 + Tailwind CSS CDN — Complete Project Setup

---

## // =========================  
## 1. 📁 Folder Structure  
## // =========================  

```
project-root/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── OTPController.php
│   │   │   ├── OrderController.php
│   │   │   ├── AdminController.php
│   │   │   └── PDFController.php
│   │   └── Livewire/
│   │       ├── Auth/
│   │       │   ├── LoginForm.php
│   │       │   └── OtpVerification.php
│   │       ├── Pages/
│   │       │   ├── Home.php
│   │       │   ├── About.php
│   │       │   ├── PriceList.php
│   │       │   ├── OrderNow.php
│   │       │   ├── PaymentOptions.php
│   │       │   ├── Contact.php
│   │       │   └── TrackOrder.php
│   │       ├── User/
│   │       │   ├── Dashboard.php
│   │       │   ├── Orders.php
│   │       │   └── ManualPayment.php
│   │       └── Admin/
│   │           ├── Dashboard.php
│   │           ├── OrderManager.php
│   │           ├── StockManager.php
│   │           ├── PaymentVerifier.php
│   │           └── WhatsAppTrigger.php
│
│   ├── Models/
│   │   ├── User.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── OrderLog.php
│   │   ├── Stock.php
│   │   └── Payment.php
│
│   └── Services/
│       ├── OTPService.php
│       ├── WhatsAppService.php
│       └── PDFService.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   └── admin.blade.php
│   │   ├── livewire/
│   │   │   ├── auth/
│   │   │   ├── pages/
│   │   │   ├── user/
│   │   │   └── admin/
│   │   ├── pdf/
│   │   │   ├── price-list.blade.php
│   │   │   └── order-confirmation.blade.php
│   │   └── components/
│   │       └── nav.blade.php
│
├── public/
│   ├── index.php
│   └── assets/
│       ├── images/
│       └── js/
│
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── admin.php
│
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_orders_table.php
│   │   ├── create_order_items_table.php
│   │   ├── create_order_logs_table.php
│   │   ├── create_stocks_table.php
│   │   └── create_payments_table.php
│   └── seeders/
│
├── storage/
│   └── app/public/pdfs/
│       ├── orders/
│       └── price-list/
│
├── .env
├── artisan
└── composer.json
```