<?php
include "header.php";

$id = $_GET['id'];

if (isset($_POST['sub'])) {
    $title = $_POST['titre'];
    $z = $_POST['z'];
    $sort = $_POST['sort'];
    $src = $_POST['src'];

    $update = $conn->prepare('UPDATE menus SET Title=? , Z=? , Sort=? , Src=? WHERE Id=?');
    $update->bindValue(1, $title); 
    $update->bindValue(2, $z);
    $update->bindValue(3, $sort);
    $update->bindValue(4, $src);
    $update->bindValue(5, $id);

    $update->execute();
}

$gt = $conn->prepare('SELECT * FROM menus WHERE Id=?');
$gt->bindValue(1, $id);
$gt->execute();
$gt = $gt->fetch(PDO::FETCH_ASSOC);


$allMenus = $conn->prepare('SELECT * FROM menus');
$allMenus->execute();
$allMenus = $allMenus->fetchAll(PDO::FETCH_ASSOC);

?>


<div class="card-box">
    <form method="POST">
        <input type="text" name="titre" placeholder="عنوان منو" class="form-control" value="<?php echo $gt['Title'] ?>" required><br>
        <input type="text" name="src" placeholder="لینک منو" class="form-control" value="<?php echo $gt['Src'] ?>" required><br>

        <select name="z" class="form-control">
            <option value="0" class="form-control">بدون دسته بندی</option>

            <?php foreach ($allMenus as $allMenu) { ?>
                <option value="<?php echo $allMenu['Id']; ?>" class="form-control">
                    <?php echo $allMenu['Title'];?>
                </option>
            <?php } ?>

        </select><br>

        <input type="number" class="form-control" name="sort" placeholder="اولویت بندی" value="<?php echo $gt['Sort'] ?>" required><br>

        <input type="submit" name="sub" value="ویرایش منو" class="btn btn-primary">
        <a href="menu.php" class="btn btn-warning">بازگشت</a>

    </form><br>


</div>
<?php include "footer.php" ?>


</body>

</html>