<?php
    $sql = "SELECT * FROM `nguoi_dung` WHERE 1";
    $danhsach = $connect->query($sql);

    if (!$danhsach) {
        die("Không thể thực hiện câu lệnh SQL: " . $connect->error);
    }
?>

<div class="user-container">
    <h3>Danh sách người dùng</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Địa Chỉ</th>
                <th>Số Điện Thoại</th>
                <th>Vai Trò</th>
                <th colspan="3">Hành động</th> 
            </tr>
        </thead>
        <tbody>
            <?php
                while ($dong = $danhsach->fetch_array(MYSQLI_ASSOC)) 
                {
                    $ho_ten_an_toan = htmlspecialchars($dong['ho_ten']);
                    //Dùng để tránh bị lỗi nháy đơn
                    $ho_ten_js = htmlspecialchars($dong['ho_ten'], ENT_QUOTES);
                    
                    echo "<tr>";
                        echo "<td>" . $dong["id_nguoi_dung"] . "</td>";
                        echo "<td>" . htmlspecialchars($dong["ten_dang_nhap"]) . "</td>";
                        echo "<td>" . $ho_ten_an_toan . "</td>";
                        echo "<td>" . htmlspecialchars($dong["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($dong["dia_chi"]) . "</td>";
                        echo "<td>" . htmlspecialchars($dong["so_dien_thoai"]) . "</td>";
                        
                        echo "<td>";
                            if($dong["vai_tro"] == 1)
                                echo "Quản trị (<a href='TrangAdmin.php?do=nguoidung_kichhoat&id=" . $dong["id_nguoi_dung"] . "&quyen=0' class='role-link lower-role'>Hạ quyền</a>)";
                            else
                                echo "Thành viên (<a href='TrangAdmin.php?do=nguoidung_kichhoat&id=" . $dong["id_nguoi_dung"] . "&quyen=1' class='role-link raise-role'>Nâng quyền</a>)";
                        echo "</td>";

                        echo "<td class='action-links'>"; 
                            if($dong["Khoa"] == 0)
                                echo "<a href='TrangAdmin.php?do=nguoidung_kichhoat&id=" . $dong["id_nguoi_dung"] . "&khoa=1' title='Đang hoạt động - Bấm để khóa'>
                                        <i class='fa-solid fa-circle-check icon-unlock'></i>
                                      </a>";
                            else
                                echo "<a href='TrangAdmin.php?do=nguoidung_kichhoat&id=" . $dong["id_nguoi_dung"] . "&khoa=0' title='Đã khóa - Bấm để mở'>
                                        <i class='fa-solid fa-ban icon-lock'></i>
                                      </a>";
                        echo "</td>";
                        
                        echo "<td class='action-links'>
                                <a href='TrangAdmin.php?do=nguoidung_sua&id=" . $dong["id_nguoi_dung"] . "' title='Sửa'>
                                    <i class='fa-solid fa-pen-to-square icon-edit'></i>
                                </a>
                              </td>";
                        
                        echo "<td class='action-links'>
                                <a href='TrangAdmin.php?do=nguoidung_xoa&id=" . $dong["id_nguoi_dung"] . "' 
                                   onclick='return confirm(\"Bạn có muốn xóa người dùng " . $ho_ten_js . " không?\")' 
                                   title='Xóa'>
                                    <i class='fa-solid fa-trash-can icon-delete'></i>
                                </a>
                              </td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    <a href="TrangAdmin.php?do=dangky" class="btn-add-user">Thêm mới người dùng</a>
</div>

<?php
    $danhsach->free();
?>