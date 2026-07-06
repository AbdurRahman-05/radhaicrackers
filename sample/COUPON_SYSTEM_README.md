# Coupon System Implementation

## Overview

A comprehensive coupon management system for the Laravel e-commerce application that supports multiple types of discounts and bonus offers. This system allows administrators to create, manage, and track coupon usage while providing customers with a seamless discount experience.

## Features

### 🎫 Coupon Types
1. **Percentage Discount** - Apply percentage-based discounts (e.g., 20% off)
2. **Fixed Amount Discount** - Apply fixed amount discounts (e.g., ₹100 off)
3. **Bonus Items** - Add free bonus products to orders

### 🔧 Admin Features
- **Coupon Management Dashboard** - View all coupons with statistics
- **Create/Edit Coupons** - Comprehensive form with all options
- **Usage Tracking** - Monitor coupon usage and performance
- **Auto Code Generation** - Generate unique coupon codes
- **Bulk Operations** - Manage multiple coupons efficiently
- **Analytics** - Track coupon performance and usage patterns

### 🛡️ Validation & Security
- **Usage Limits** - Set total and per-user usage limits
- **Validity Periods** - Start and expiry dates
- **Minimum Order Amounts** - Require minimum purchase
- **Category Restrictions** - Apply to specific product categories
- **Product Exclusions** - Exclude specific products
- **User Limits** - Control per-user usage

### 📊 Analytics & Reporting
- **Usage Statistics** - Track total and individual usage
- **Performance Metrics** - Monitor discount effectiveness
- **User Behavior** - Analyze coupon usage patterns
- **Revenue Impact** - Measure discount impact on sales

## Database Structure

### Coupons Table
```sql
- id (Primary Key)
- code (Unique coupon code)
- name (Coupon name)
- description (Optional description)
- type (percentage, fixed_amount, bonus_items)
- value (Discount value or percentage)
- minimum_order_amount (Minimum order requirement)
- maximum_discount (Max discount for percentage)
- usage_limit (Total usage limit)
- used_count (Current usage count)
- user_limit (Per-user usage limit)
- starts_at (Start date)
- expires_at (Expiry date)
- is_active (Active status)
- applies_to_categories (JSON array)
- excluded_products (JSON array)
- bonus_product_id (Foreign key to stocks)
- bonus_quantity (Quantity of bonus items)
- created_at, updated_at, deleted_at
```

### Coupon Usages Table
```sql
- id (Primary Key)
- coupon_id (Foreign key to coupons)
- user_id (Foreign key to users)
- order_id (Foreign key to orders)
- discount_amount (Amount discounted)
- bonus_items_added (JSON array)
- used_at (Usage timestamp)
- created_at, updated_at
```

## Installation & Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Sample Data (Optional)
```bash
php artisan db:seed --class=CouponSeeder
```

### 3. Register Service Provider
Add to `config/app.php`:
```php
'providers' => [
    // ...
    App\Providers\CouponServiceProvider::class,
],
```

## Usage Examples

### Creating a Coupon

#### Percentage Discount
```php
$coupon = Coupon::create([
    'code' => 'SUMMER20',
    'name' => 'Summer Sale 20% Off',
    'type' => 'percentage',
    'value' => 20,
    'minimum_order_amount' => 1000,
    'maximum_discount' => 500,
    'usage_limit' => 100,
    'expires_at' => now()->addMonths(1),
]);
```

#### Fixed Amount Discount
```php
$coupon = Coupon::create([
    'code' => 'FLAT100',
    'name' => 'Flat ₹100 Off',
    'type' => 'fixed_amount',
    'value' => 100,
    'minimum_order_amount' => 500,
    'usage_limit' => 50,
]);
```

#### Bonus Items
```php
$coupon = Coupon::create([
    'code' => 'BONUSGIFT',
    'name' => 'Free Bonus Item',
    'type' => 'bonus_items',
    'bonus_product_id' => $productId,
    'bonus_quantity' => 2,
    'minimum_order_amount' => 800,
]);
```

### Applying Coupons

#### Using CouponService
```php
$couponService = app(CouponService::class);

// Validate coupon
$result = $couponService->validateCoupon(
    'SUMMER20',
    $userId,
    $orderAmount,
    $orderItems
);

if ($result['valid']) {
    // Apply coupon to order
    $result = $couponService->applyCouponToOrder(
        $result['coupon'],
        $order,
        $orderItems
    );
}
```

#### Using Livewire Component
```php
// In your Livewire component
public function applyCoupon()
{
    $couponService = app(CouponService::class);
    $result = $couponService->validateCoupon(
        $this->couponCode,
        auth()->id(),
        $this->orderAmount
    );
    
    if ($result['valid']) {
        $this->appliedCoupon = $result['coupon'];
        $this->discountAmount = $couponService->getCouponSummary(
            $result['coupon'], 
            $this->orderAmount
        )['discount_amount'];
    }
}
```

## Admin Interface

### Dashboard Integration
The coupon system is integrated into the admin dashboard with:
- Quick access to coupon management
- Usage statistics overview
- Recent coupon activity

### Coupon Management
- **List View**: All coupons with search and filters
- **Create Form**: Comprehensive form with validation
- **Edit Form**: Modify existing coupons
- **Usage Tracking**: Detailed usage history
- **Status Toggle**: Activate/deactivate coupons

### Features in Admin Panel
1. **Statistics Cards**
   - Total coupons
   - Active coupons
   - Expiring soon
   - Total usage

2. **Advanced Filters**
   - By type (percentage, fixed, bonus)
   - By status (active/inactive)
   - By expiry date
   - By usage count

3. **Bulk Operations**
   - Activate multiple coupons
   - Deactivate multiple coupons
   - Delete unused coupons

## Frontend Integration

### Livewire Component
```php
// Include in your order form
<livewire:coupon-apply :order-amount="$total" :order-items="$items" />
```

### Event Handling
```javascript
// Listen for coupon events
Livewire.on('coupon-applied', (data) => {
    console.log('Coupon applied:', data);
    // Update order total, show discount, etc.
});

Livewire.on('coupon-removed', () => {
    console.log('Coupon removed');
    // Reset order total, hide discount, etc.
});
```

## API Endpoints

### Admin Routes
```
GET    /admin/coupons                    - List all coupons
GET    /admin/coupons/create            - Show create form
POST   /admin/coupons                   - Store new coupon
GET    /admin/coupons/{id}/edit         - Show edit form
PUT    /admin/coupons/{id}              - Update coupon
DELETE /admin/coupons/{id}              - Delete coupon
PATCH  /admin/coupons/{id}/toggle-status - Toggle active status
GET    /admin/coupons/{id}/usage        - Show usage history
GET    /admin/coupons/generate-code     - Generate unique code
```

## Validation Rules

### Coupon Validation
- **Code**: Required, unique, max 50 characters
- **Name**: Required, max 255 characters
- **Type**: Required, must be valid type
- **Value**: Required, numeric, min 0
- **Minimum Order**: Optional, numeric, min 0
- **Maximum Discount**: Optional, numeric, min 0
- **Usage Limits**: Optional, integer, min 1
- **Dates**: Optional, valid dates, expiry after start

### Business Logic Validation
- Bonus product must be active
- Usage limits cannot be exceeded
- User limits cannot be exceeded
- Minimum order amounts must be met
- Coupon must be within validity period

## Security Features

### Input Validation
- All inputs are validated and sanitized
- SQL injection protection
- XSS protection
- CSRF protection

### Access Control
- Admin-only access to management
- User authentication required
- Proper authorization checks

### Data Integrity
- Foreign key constraints
- Soft deletes for coupons
- Transaction safety for coupon application

## Performance Optimization

### Database Indexes
- Coupon code index for fast lookups
- Date range indexes for validity checks
- Usage tracking indexes

### Caching
- Coupon validation caching
- Usage statistics caching
- Frequently accessed data caching

### Query Optimization
- Eager loading for relationships
- Efficient pagination
- Optimized search queries

## Monitoring & Analytics

### Usage Tracking
- Track every coupon usage
- Monitor user behavior
- Analyze coupon effectiveness

### Performance Metrics
- Conversion rates
- Average order values
- Revenue impact
- User engagement

### Reporting
- Daily/weekly/monthly reports
- Export functionality
- Custom date ranges

## Troubleshooting

### Common Issues

1. **Coupon Not Applying**
   - Check if coupon is active
   - Verify minimum order amount
   - Check usage limits
   - Validate expiry date

2. **Bonus Items Not Adding**
   - Verify bonus product is active
   - Check product availability
   - Validate bonus quantity

3. **Validation Errors**
   - Check all required fields
   - Verify data types
   - Ensure proper formatting

### Debug Mode
Enable debug mode to see detailed validation messages:
```php
// In CouponService
Log::info('Coupon validation result:', $result);
```

## Future Enhancements

### Planned Features
1. **Bulk Coupon Generation** - Generate multiple coupons at once
2. **Email Integration** - Send coupon codes via email
3. **SMS Integration** - Send coupon codes via SMS
4. **Advanced Analytics** - More detailed reporting
5. **API Integration** - RESTful API for external systems
6. **Mobile App Support** - Native mobile integration

### Performance Improvements
1. **Redis Caching** - Faster coupon lookups
2. **Queue Processing** - Background coupon processing
3. **Database Optimization** - Better query performance
4. **CDN Integration** - Faster static asset delivery

## Support & Maintenance

### Regular Maintenance
- Monitor coupon usage patterns
- Clean up expired coupons
- Update usage statistics
- Backup coupon data

### Updates & Patches
- Regular security updates
- Feature enhancements
- Bug fixes
- Performance improvements

## Conclusion

The coupon system provides a robust, scalable solution for managing discounts and promotions in the e-commerce application. With comprehensive admin tools, secure validation, and detailed analytics, it offers everything needed to run successful promotional campaigns while maintaining data integrity and performance.

For additional support or feature requests, please refer to the project documentation or contact the development team. 