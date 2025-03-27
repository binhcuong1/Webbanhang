</div>
<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Thông tin liên hệ -->
            <div class="col-md-4">
                <h5>Liên hệ</h5>
                <p>Email: wheystore@webbanhang.com</p>
                <p>Điện thoại: 0123-456-789</p>
                <p>Địa chỉ: 123 Đường Mua Sắm, TP. Hồ Chí Minh</p>
            </div>
            <!-- Liên kết nhanh -->
            <div class="col-md-4">
                <h5>Liên kết nhanh</h5>
                <ul class="list-unstyled">
                    <li><a href="/webbanhang/Product/" class="text-white text-decoration-none">Danh sách sản phẩm</a></li>
                    <li><a href="/webbanhang/Product/cart" class="text-white text-decoration-none">Giỏ hàng</a></li>
                    <li><a href="/webbanhang/Category/list" class="text-white text-decoration-none">Danh mục</a></li>
                </ul>
            </div>
            <!-- Thông tin bản quyền -->
            <div class="col-md-4">
                <h5>Về chúng tôi</h5>
                <p>Web Bán Hàng - Cửa hàng trực tuyến cung cấp thực phẩm bổ sung chất lượng với giá cả hợp lý.</p>
                <p>&copy; <?php echo date('Y'); ?> Web Bán Hàng. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Nút liên hệ tròn -->
<div class="contact-button">
    <button class="contact-toggle btn btn-primary rounded-circle shadow-lg">
        <i class="fas fa-comment-alt"></i> Liên hệ
    </button>
</div>

<!-- Popup liên hệ -->
<div class="contact-popup" style="display: none;">
    <div class="contact-popup-header">
        <h5>Tư vấn đặt hàng</h5>
        <p class="text-primary">012 345 6789</p>
        <p>Chat với WheyStore để được tư vấn hiệu quả và giao tốt</p>
    </div>
    <div class="contact-options">
        <a href="https://m.me/wheystore.official" target="_blank" class="contact-option">
            <i class="fab fa-facebook-messenger"></i> Chat Messenger
        </a>
        <a href="https://zalo.me/0123456789" target="_blank" class="contact-option">
            <i class="fas fa-comment"></i> Chat trên Zalo
        </a>
        <a href="tel:0919013030" class="contact-option">
            <i class="fas fa-phone-alt"></i> 012 345 6789
        </a>
    </div>
    <button class="contact-close btn btn-primary rounded-circle shadow-lg">
        <i class="fas fa-times"></i>
    </button>
</div>

<!-- JavaScript để xử lý hiển thị/ẩn popup -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.contact-toggle');
    const closeButton = document.querySelector('.contact-close');
    const popup = document.querySelector('.contact-popup');

    toggleButton.addEventListener('click', function() {
        popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    });

    closeButton.addEventListener('click', function() {
        popup.style.display = 'none';
    });
});
</script>

<!-- Nút Back to Top -->
<button class="back-to-top" onclick="scrollToTop()" title="Quay lại đầu trang">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
// Hiển thị nút Back to Top
window.addEventListener('scroll', function() {
    const backToTop = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
        backToTop.classList.add('show');
    } else {
        backToTop.classList.remove('show');
    }
});

// Cuộn mượt mà lên đầu trang
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>