<?php
include "header.php";

$writers = $conn->prepare('SELECT * FROM writers');
$writers->execute();
$writers = $writers->fetchAll(PDO::FETCH_ASSOC);
$number = 1;


?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                    <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z" />
                </svg>
            </th>
            <th scope="col">نام کاربری</th>
            <th scope="col">نام کامل</th>
            <th scope="col">عکس</th>
            <th scope="col">شماره تلفن</th>

            <th scope="col">تعداد مقالات</th>
            <th scope="col">ویرایش، حذف</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach ($writers as $writer) { ?>
            <tr>
                <th scope="row"><?= $number++; ?></th>
                <td><?= $writer['username']; ?></td>
                <td><?= $writer['fullname']; ?></td>
                <td>
                    <img src="../../uploader/img-profile-writer/<?= $writer['image']; ?> " width="70" style="border-radius: 70px; background-color: #ffffff; padding: 1%; box-shadow: 0px 0px 8px #777b7d; width: 70px; height: 70px;">
                </td>
                <td><?= $writer['phone_number']; ?></td>
                <td><?= $writer['id']; ?></td>
                <td>
                    <form method="POST">
                        <a href="editwriter.php?id=<?= $writer['id']; ?>"><input type="submit" value="ویرایش" class="btn btn-warning"></a>
                        <a href="deletewriter.php?id=<?= $writer['id']; ?>"> <input type="submit" value="حذف" class="btn btn-danger"></a>
                    </form>
                </td>

            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include "footer.php"; ?>