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