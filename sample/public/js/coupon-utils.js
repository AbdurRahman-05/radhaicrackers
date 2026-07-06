/**
 * Coupon Utilities for Frontend
 * Handles coupon validation and application
 */

class CouponUtils {
    constructor() {
        this.baseUrl = '/admin/api';
        this.appliedCoupon = null;
    }

    /**
     * Validate a coupon code
     * @param {string} code - Coupon code
     * @param {number} orderAmount - Order total amount
     * @param {Array} orderItems - Order items array
     * @returns {Promise} - Validation result
     */
    async validateCoupon(code, orderAmount = 0, orderItems = []) {
        try {
            const response = await fetch(`${this.baseUrl}/coupons/validate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    code: code.toUpperCase(),
                    order_amount: orderAmount,
                    order_items: orderItems
                })
            });

            const result = await response.json();
            
            if (result.success) {
                this.appliedCoupon = result.coupon;
                return {
                    valid: true,
                    coupon: result.coupon,
                    message: result.message
                };
            } else {
                return {
                    valid: false,
                    message: result.message
                };
            }
        } catch (error) {
            console.error('Error validating coupon:', error);
            return {
                valid: false,
                message: 'Network error. Please try again.'
            };
        }
    }

    /**
     * Get available coupons for current order
     * @param {number} orderAmount - Order total amount
     * @param {string} category - Product category
     * @returns {Promise} - Available coupons
     */
    async getAvailableCoupons(orderAmount = 0, category = null) {
        try {
            const params = new URLSearchParams({
                order_amount: orderAmount
            });
            
            if (category) {
                params.append('category', category);
            }

            const response = await fetch(`${this.baseUrl}/coupons/available?${params}`);
            const result = await response.json();
            
            return result.success ? result.coupons : [];
        } catch (error) {
            console.error('Error fetching available coupons:', error);
            return [];
        }
    }

    /**
     * Apply coupon to order
     * @param {Object} coupon - Coupon object
     * @param {number} orderAmount - Current order amount
     * @returns {Object} - Updated order details
     */
    applyCouponToOrder(coupon, orderAmount) {
        let discountAmount = 0;
        let newTotal = orderAmount;

        switch (coupon.type) {
            case 'percentage':
                discountAmount = (orderAmount * coupon.value) / 100;
                if (coupon.maximum_discount) {
                    discountAmount = Math.min(discountAmount, coupon.maximum_discount);
                }
                newTotal = orderAmount - discountAmount;
                break;

            case 'fixed_amount':
                discountAmount = Math.min(coupon.value, orderAmount);
                newTotal = orderAmount - discountAmount;
                break;

            case 'bonus_items':
                // Bonus items don't reduce order amount
                discountAmount = 0;
                newTotal = orderAmount;
                break;
        }

        return {
            originalAmount: orderAmount,
            discountAmount: discountAmount,
            newTotal: newTotal,
            coupon: coupon
        };
    }

    /**
     * Remove applied coupon
     */
    removeCoupon() {
        this.appliedCoupon = null;
        return {
            originalAmount: 0,
            discountAmount: 0,
            newTotal: 0,
            coupon: null
        };
    }

    /**
     * Format currency
     * @param {number} amount - Amount to format
     * @returns {string} - Formatted amount
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR'
        }).format(amount);
    }

    /**
     * Show coupon success message
     * @param {string} message - Success message
     */
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }

    /**
     * Show coupon error message
     * @param {string} message - Error message
     */
    showErrorMessage(message) {
        this.showMessage(message, 'error');
    }

    /**
     * Show message with type
     * @param {string} message - Message to show
     * @param {string} type - Message type (success/error)
     */
    showMessage(message, type) {
        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `coupon-message ${type === 'success' ? 'success' : 'error'}`;
        messageDiv.innerHTML = `
            <div class="message-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="close-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Add styles
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 12px 16px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease-out;
        `;

        if (type === 'success') {
            messageDiv.style.backgroundColor = '#10b981';
        } else {
            messageDiv.style.backgroundColor = '#ef4444';
        }

        // Add to page
        document.body.appendChild(messageDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentElement) {
                messageDiv.remove();
            }
        }, 5000);
    }

    /**
     * Initialize coupon form
     * @param {string} formSelector - Form selector
     * @param {Function} onCouponApplied - Callback when coupon is applied
     * @param {Function} onCouponRemoved - Callback when coupon is removed
     */
    initCouponForm(formSelector, onCouponApplied = null, onCouponRemoved = null) {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const codeInput = form.querySelector('input[name="coupon_code"]');
        const applyBtn = form.querySelector('button[type="submit"]');
        const removeBtn = form.querySelector('.remove-coupon');

        if (applyBtn) {
            applyBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                
                const code = codeInput.value.trim();
                if (!code) {
                    this.showErrorMessage('Please enter a coupon code');
                    return;
                }

                applyBtn.disabled = true;
                applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';

                try {
                    const result = await this.validateCoupon(code);
                    
                    if (result.valid) {
                        this.showSuccessMessage(result.message);
                        if (onCouponApplied) {
                            onCouponApplied(result.coupon);
                        }
                    } else {
                        this.showErrorMessage(result.message);
                    }
                } catch (error) {
                    this.showErrorMessage('Error applying coupon');
                } finally {
                    applyBtn.disabled = false;
                    applyBtn.innerHTML = 'Apply Coupon';
                }
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.removeCoupon();
                if (onCouponRemoved) {
                    onCouponRemoved();
                }
                this.showSuccessMessage('Coupon removed successfully');
            });
        }
    }
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .coupon-message .message-content {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .coupon-message .close-btn {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
    }

    .coupon-message .close-btn:hover {
        opacity: 0.8;
    }
`;
document.head.appendChild(style);

// Export for use
window.CouponUtils = CouponUtils; 