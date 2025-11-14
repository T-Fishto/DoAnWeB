const menuBar = document.querySelector(".menu_bar");
const menuItems = document.querySelector(".menu-Items"); //lấy các phần tử có class menu-Items rồi gán vào biến menuItems
menuBar.addEventListener("click", function() {
    menuBar.classList.toggle("active");//đang ẩn cái menu
    // toggle làm thay đổi trạng thái, nếu có class active thì bỏ đi, không có thì thêm vào
    //document.querySelector(".menu-Items").classList.toggle("active"); khi click vào menu-bar thì menu-Items sẽ trượt vào
    menuItems.classList.toggle("active");
});
//Sự kiện cuộn trang
window.addEventListener("scroll",function(){
    const x = this.pageYOffset;
    // console.log(x); để xem các vị trí để bắt sự kiện
    if(x > 80 ){this.document.querySelector(".top").classList.add("active")}
    else {this.document.querySelector(".top").classList.remove("active")}

})
// --- JAVASCRIPT CHO MODAL THÔNG TIN ---

// Chờ cho toàn bộ HTML tải xong
document.addEventListener('DOMContentLoaded', function() {
    
    // Tìm các nút
    const aboutModal = document.getElementById('about-modal');
    const openModalBtn = document.getElementById('open-about-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');

    // Kiểm tra xem các nút có tồn tại không
    if (openModalBtn && aboutModal && closeModalBtn) {
        
        // Bấm "Thông Tin" -> Mở modal
        openModalBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn link nhảy
            aboutModal.classList.add('show'); // Hiển thị modal
        });

        // Bấm nút "Đóng" -> Đóng modal
        closeModalBtn.addEventListener('click', function() {
            aboutModal.classList.remove('show'); // Ẩn modal
        });

        // Bấm vào nền mờ -> Đóng modal
        aboutModal.addEventListener('click', function(e) {
            if (e.target === aboutModal) {
                aboutModal.classList.remove('show'); // Ẩn modal
            }
        });
    }
});