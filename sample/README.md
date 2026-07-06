# Radhe Crackers - Laravel Livewire Website

A modern, responsive fireworks e-commerce website built with Laravel Livewire and Tailwind CSS, inspired by the original Radhe Crackers website.

## 🎆 Features

### Core Functionality
- **Responsive Design**: Mobile-first approach with beautiful UI/UX
- **Product Catalog**: Browse fireworks by categories (Bombs, Rockets, Sparklers, etc.)
- **Search Functionality**: Real-time search with dropdown results
- **Shopping Cart**: Session-based cart with quantity management
- **Best Offers**: Special deals and discounted products
- **Order Management**: Complete order processing system
- **Admin Panel**: Full admin dashboard for managing orders, products, and users

### Technical Features
- **Laravel Livewire**: Real-time interactions without JavaScript bundlers
- **Tailwind CSS**: Utility-first CSS framework via CDN
- **Mobile Responsive**: Optimized for all device sizes
- **Session Management**: Secure user authentication and cart handling
- **PDF Generation**: Order confirmations and price lists
- **WhatsApp Integration**: Order summaries and OTP links

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Web server (Apache/Nginx) or PHP built-in server

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Cracker
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=radhe_crackers
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - Frontend: http://localhost:8000
   - Admin Panel: http://localhost:8000/admin/login
   - Default admin credentials: admin@radhecrackers.com / password

## 📁 Project Structure

```
Cracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Traditional controllers
│   │   │   ├── Admin/           # Admin dashboard components
│   │   │   ├── Auth/            # Authentication components
│   │   │   ├── Components/      # Reusable components
│   │   │   ├── Pages/           # Page-specific components
│   │   │   └── User/            # User dashboard components
│   │   └── Middleware/          # Custom middleware
│   ├── Models/                  # Eloquent models
│   ├── Services/                # Business logic services
│   └── Providers/               # Service providers
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── resources/
│   └── views/
│       ├── layouts/             # Blade layouts
│       ├── pages/               # Static pages
│       ├── livewire/            # Livewire component views
│       └── admin/               # Admin panel views
└── routes/
    └── web.php                  # Web routes
```

## 🎨 Design Features

### Color Scheme
- **Primary**: Orange (#b37a2c) - Warm, festive feel
- **Secondary**: Dark Purple (#1e093b) - Premium look
- **Accent**: Gold (#ffca49) - Celebration theme
- **Background**: Light grays and whites for clean design

### Typography
- **Font Family**: Jost (Google Fonts)
- **Headings**: Bold, capitalized for impact
- **Body Text**: Clean, readable sans-serif

### Components
- **Product Cards**: Hover effects with lift animations
- **Category Cards**: Gradient backgrounds with icons
- **Buttons**: Gradient backgrounds with hover states
- **Navigation**: Dropdown menus with smooth transitions
- **Cart**: Slide-out sidebar with real-time updates

## 🔧 Configuration

### Tailwind CSS
The project uses Tailwind CSS via CDN for simplicity:
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### Livewire
Real-time interactions without JavaScript bundlers:
```php
@livewireStyles
@livewireScripts
```

### Custom Styles
Custom CSS variables and animations defined in the layout:
```css
:root {
    --primary-color: #b37a2c;
    --secondary-color: #1e093b;
    --accent-color: #ffca49;
}
```

## 📱 Responsive Design

### Breakpoints
- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

### Mobile Features
- Collapsible navigation menu
- Touch-friendly buttons and inputs
- Optimized product grids
- Swipe-friendly cart interface

## 🛒 E-commerce Features

### Product Management
- **Categories**: 8 main firework categories
- **Pricing**: Support for original and discounted prices
- **Inventory**: Stock tracking with active/inactive status
- **Descriptions**: Detailed product information

### Shopping Experience
- **Search**: Real-time product search
- **Filtering**: Category-based filtering
- **Cart**: Session-based shopping cart
- **Checkout**: Streamlined order process

### Order Processing
- **Order Tracking**: Real-time order status
- **PDF Generation**: Order confirmations
- **WhatsApp Integration**: Order summaries
- **Payment Options**: Multiple payment methods

## 👥 User Management

### Authentication
- **Phone-based OTP**: Secure login system
- **Session Management**: Secure user sessions
- **Role-based Access**: Admin and user roles

### User Features
- **Dashboard**: Order history and tracking
- **Profile Management**: Update personal information
- **Order History**: View past orders

## 🔐 Admin Panel

### Dashboard Features
- **Order Management**: View and update order status
- **Product Management**: Add, edit, and manage products
- **User Management**: View and manage user accounts
- **Analytics**: Sales and order analytics

### Admin Functions
- **Stock Management**: Add and update inventory
- **PDF Manager**: Upload and manage price lists
- **WhatsApp Links**: Generate order and OTP links
- **Settings**: Configure business information

## 🚀 Deployment

### Production Setup
1. Set environment to production
2. Configure database for production
3. Set up web server (Apache/Nginx)
4. Configure SSL certificate
5. Set up backup system

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

## 🧪 Testing

### Running Tests
```bash
php artisan test
```

### Test Coverage
- Unit tests for models and services
- Feature tests for controllers
- Browser tests for Livewire components

## 📈 Performance

### Optimization
- **Database Indexing**: Optimized queries
- **Caching**: Session and query caching
- **Image Optimization**: Compressed product images
- **CDN**: Static asset delivery

### Monitoring
- **Error Logging**: Comprehensive error tracking
- **Performance Monitoring**: Response time tracking
- **User Analytics**: Visitor behavior tracking

## 🔧 Maintenance

### Regular Tasks
- **Database Backups**: Daily automated backups
- **Security Updates**: Regular Laravel updates
- **Performance Monitoring**: Regular performance checks
- **Content Updates**: Regular product updates

### Troubleshooting
- **Log Files**: Check Laravel logs for errors
- **Database**: Verify database connections
- **Permissions**: Check file and folder permissions
- **Cache**: Clear application cache if needed

## 📞 Support

### Contact Information
- **Business Enquiry**: +91 8807060809 / +91 9751048974
- **Office Hours**: 9am To 5pm
- **Email**: radhecrackers@gmail.com
- **Address**: 3/180-5, Virudhunagar-Sivakasi main road, G.N. Patti, Amathur - 626005

### Documentation
- **Laravel Documentation**: https://laravel.com/docs
- **Livewire Documentation**: https://laravel-livewire.com/docs
- **Tailwind CSS Documentation**: https://tailwindcss.com/docs

## 📄 License

This project is proprietary software. All rights reserved.

## 🙏 Acknowledgments

- **Laravel Team**: For the amazing PHP framework
- **Livewire Team**: For real-time components
- **Tailwind CSS Team**: For the utility-first CSS framework
- **Original Radhe Crackers**: For the inspiration and design

---

**Built with ❤️ using Laravel, Livewire, and Tailwind CSS**
