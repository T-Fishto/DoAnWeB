function validateForm() 
{
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const errorElement = document.getElementById('errorMessage');

    errorElement.textContent = '';
    errorElement.classList.add('hidden');

    if (password !== confirmPassword) {
        errorElement.textContent = "Lỗi: Mật khẩu và Xác nhận mật khẩu không khớp!";
        errorElement.classList.remove('hidden');
        return false; 
    } 
    
    return true;
}