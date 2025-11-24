<?php
    $sql = "SELECT * FROM `danh_muc` ORDER BY id_danh_muc DESC";
    $danhsach = $connect->query($sql);
?>

<div class="user-container"> <h3>Danh sách Danh Mục</h3>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Danh Mục</th>
                <th>Mô Tả</th>
                <th colspan="2">Hành động</th> 
            </tr>
        </thead>
        <tbody>
            <?php
                if ($danhsach->num_rows > 0) {
                    while ($dong = $danhsach->fetch_assoc()) 
                    {
                        echo "<tr>";
                            echo "<td>" . $dong["id_danh_muc"] . "</td>";
                            echo "<td><strong>" . htmlspecialchars($dong["ten_danh_muc"]) . "</strong></td>";
                            echo "<td>" . htmlspecialchars($dong["mo_ta"]) . "</td>";
                            
                            // Nút Sửa
                            echo "<td class='action-links'>
                                    <a href='TrangAdmin.php?do=danhmuc_sua&id=" . $dong["id_danh_muc"] . "' title='Sửa'>
                                        <i class='fa-solid fa-pen-to-square icon-edit'></i>
                                    </a>
                                  </td>";
                            
                            // Nút Xóa
                            echo "<td class='action-links'>
                                    <a href='TrangAdmin.php?do=danhmuc_xoa&id=" . $dong["id_danh_muc"] . "' 
                                       onclick='return confirm(\"Cảnh báo: Xóa danh mục này có thể làm mất sản phẩm thuộc về nó! Bạn chắc chắn xóa?\")' 
                                       title='Xóa'>
                                        <i class='fa-solid fa-trash-can icon-delete'></i>
                                    </a>
                                  </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Chưa có danh mục nào.</td></tr>";
                }
            ?>
        </tbody>
    </table>
    <a href="TrangAdmin.php?do=danhmuc_them" class="btn-add-user">Thêm Danh Mục Mới</a>
</div>