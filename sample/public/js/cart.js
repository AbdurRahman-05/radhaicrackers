// Robust addToCart function for saving current product values
function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cartItems') || '[]');
    cart.push({
        product_name: product.name,         // Current name
        content: product.content,           // Current content/description
        rate: product.price,                // Current price
        quantity: product.qty,              // User-selected quantity
        total: product.price * product.qty  // Calculated total
    });
    localStorage.setItem('cartItems', JSON.stringify(cart));
}
// Usage example (call this on your add-to-cart button):
// addToCart({name: 'Super Rocket', content: 'Pack of 10', price: 120, qty: 2}); 