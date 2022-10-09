<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$images = $dbh->prepare("SELECT * FROM `course_image`");

require_once('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>IMAGES</ins></h1>
<div>
    <form method="post" action="image_delete.php" id="images-delete-form">
        <div class="section text-center">
        <input class="btn btn-danger" type="submit" value="Delete selected images">
        <?php if ($images->execute() && $images->rowCount() > 0) { ?>
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Select</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($image = $images->fetchObject()) { ?>
                    <tr>
                        <td><input type="checkbox" name="image_ids[]"  value="<?= $image->id ?>"/></td>
                        <td>
                            <div class="row">
                                <p><img src="course_images/<?= $image->filePath ?>" width = 30% height="auto"></p>
                            </div>
                        </td>
                        <td>
                            <button type="submit" name="image_ids[]" value="<?= $image->id ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else {?>
            <p>There's no data in images</p>
        <?php } ?>
        </div>
    </form>
</div>
</body>
</html>