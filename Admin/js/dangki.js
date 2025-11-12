function validateForm() 
{
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const errorElement = document.getElementById('errorMessage');

    if (password !== confirmPassword) {
        errorElement.textContent = "Lỗi: Mật khẩu và Xác nhận mật khẩu không khớp!";
        errorElement.classList.remove('hidden');
        return false;
    } 
    else 
    {
        errorElement.classList.add('hidden');
                
        console.log('Dữ liệu hợp lệ. Form sẵn sàng để gửi.');
                                
        return false;
    }
}