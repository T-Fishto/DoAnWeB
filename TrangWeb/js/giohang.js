// Chờ cho trang tải xong
document.addEventListener('DOMContentLoaded', function() {
    
    // Lấy tất cả các element cần thiết
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const productItems = document.querySelectorAll('.product-item');
    const subtotalEl = document.getElementById('cart-subtotal');
    const shippingEl = document.getElementById('cart-shipping');
    const totalEl = document.getElementById('cart-total');
    
    // Lấy phí ship cố định từ data-attribute
    // Thêm kiểm tra 'shippingEl' có tồn tại không
    if (shippingEl) {
        const baseShippingFee = parseFloat(shippingEl.dataset.shippingFee);

        // Hàm định dạng tiền tệ (vd: 50000 -> 50.000đ)
        function formatCurrency(number) {
            // Dùng API của trình duyệt để định dạng tiền Việt Nam
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(number).replace('₫', 'đ');
        }

        // Hàm tính toán lại tổng tiền
        function recalculateTotal() {
            let newSubtotal = 0;
            
            // Lặp qua từng sản phẩm
            productItems.forEach(item => {
                const checkbox = item.querySelector('.product-checkbox');
                
                // Nếu sản phẩm được check
                if (checkbox.checked) {
                    const price = parseFloat(item.dataset.price);
                    const quantity = parseInt(item.dataset.quantity);
                    newSubtotal += (price * quantity);
                }
            });

            // Tính phí ship (nếu không mua gì thì free ship)
            const newShipping = newSubtotal > 0 ? baseShippingFee : 0;
            
            // Tính tổng cộng
            const newTotal = newSubtotal + newShipping;

            // Cập nhật HTML (thêm kiểm tra tồn tại)
            if (subtotalEl) subtotalEl.textContent = formatCurrency(newSubtotal);
            if (shippingEl) shippingEl.textContent = formatCurrency(newShipping);
            if (totalEl) totalEl.textContent = formatCurrency(newTotal);
        }

        // Gắn "tai nghe" sự kiện 'change' cho TẤT CẢ các checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', recalculateTotal);
        });

        // Tính toán tổng tiền một lần khi trang vừa tải
        recalculateTotal();
    }
});