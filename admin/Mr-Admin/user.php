<?php
include "header.php";
$numberMenu = 1;
$users = $conn->prepare('SELECT * FROM users');
$users->execute();



?>

<div class="card-box">
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                        <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z" />
                    </svg>
                </th>
                <th scope="col">نام کاربری</th>
                <th scope="col">ایمیل</th>
                <th scope="col">پسوورد</th>
                <th scope="col">سطح کاربری</th>

                <th scope="col">حذف، ویرایش</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <th scope="row"><?php echo $numberMenu++; ?></th>
                    <td><?php echo $user['UserName'] ?></td>
                    <td><?php echo $user['Email'] ?></td>
                    <td><?php echo $user['Password']; ?></td>
                    <td><?php if ($user['UserLevel'] == 1) {
                            echo "کاربر معمولی";
                        } elseif ($user['UserLevel'] == 2) {
                            echo "مدرس";
                        } elseif ($user['UserLevel'] == 3) {
                            echo "نویسنده";
                        } elseif ($user['UserLevel'] == 4) {
                            echo "مدیر وبسایت";
                        }  ?></td>
                    <td>
                        <a href="deleteuser.php?id=<?php echo $user['Id']; ?>" class="btn btn-danger">حذف</a>
                        <a href="edituser.php?id=<?php echo $user['Id']; ?>" class="btn btn-warning">ویرایش</a>

                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>
<?php include "footer.php" ?>


</body>

</html>