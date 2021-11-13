<?php
include "header.php";

$numberMenu = 1;
$succesmenu = null;
if (isset($_POST['sub'])) {
    $title = $_POST['titre'];
    $z = $_POST['z'];
    $sort = $_POST['sort'];
    $src = $_POST['src'];


    $result = $conn->prepare('INSERT INTO menus SET Title=?  , Z=? , Sort=? , Src=?');
    $result->bindValue(1, $title);
    $result->bindValue(2, $z);
    $result->bindValue(3, $sort);
    $result->bindValue(4, $src);

    $result->execute();

    $succesmenu = true;
}

$allMenus = $conn->prepare('SELECT * FROM menus');
$allMenus->execute();
$allMenus = $allMenus->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="card-box">
    <form method="POST">
        <input type="text" name="titre" placeholder="عنوان منو" class="form-control" required><br>
        <input type="text" name="src" placeholder="لینک منو" class="form-control" required><br>

        <select name="z" class="form-control">
            <option value="0" class="form-control">بدون دسته بندی</option>
            <?php foreach ($allMenus as $allMenu) { ?>

                <option value="<?php echo $allMenu['Id']; ?>" class="form-control">
                    <?php echo $allMenu['Title']; ?>
                </option>
            <?php } ?>

        </select><br>

        <input type="number" class="form-control" name="sort" placeholder="اولویت بندی" required><br>

        <input type="submit" name="sub" value="افزودن منو" class="btn btn-primary">
    </form><br>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                        <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z" />
                    </svg>
                </th>
                <th scope="col">عنوان منو</th>
                <th scope="col">زیر مجموعه</th>
                <th scope="col">وضعیت قرارگیری</th>
                <th scope="col">حذف، ویرایش</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($allMenus as $allMenu) { ?>
                <tr>
                    <th scope="row"><?php echo $numberMenu++; ?></th>
                    <td><?php echo $allMenu['Title'] ?></td>
                    <td>
                        <?php
                        foreach ($allMenus as $item) {
                            if ($allMenu['Z'] == $item['Id']) {
                                echo $item['Title'];
                            }
                        }
                        ?>
                    </td>
                    <td><?php echo $allMenu['Sort']; ?></td>
                    <td>
                        <a href="deletemenu.php?id=<?php echo $allMenu['Id']; ?>" class="btn btn-danger">حذف</a>
                        <a href="editmenu.php?id=<?php echo $allMenu['Id']; ?>" class="btn btn-warning">ویرایش</a>

                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>
<?php include "footer.php" ?>


</body>

</html>

<?php

if ($succesmenu) {
?>
    <script>
        toastr.success('منو با موففقیت اضافه شد', 'تبریک');
    </script>
<?php
}


?>