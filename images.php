<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$images = $dbh->prepare("SELECT * FROM `course_image`");

require_once('home.html');
?>

<h1>IMAGES</h1>
<div>
    <form method="post" action="image_delete.php" id="images-delete-form">
        <input type="submit" value="Delete selected images">
        <?php if ($images->execute() && $images->rowCount() > 0) { ?>
            <table>
                <thead>
                <tr>
                    <th>Select</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($image = $images->fetchObject()) { ?>
                    <tr>
                        <td><input type="checkbox" name="image_ids[]" value="<?= $image->id ?>"/></td>
                        <td>
                            <div class="row">
                                <p><img src="course_images/<?= $image->filePath ?>"/></p>
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
    </form>
</div>
</body>
</html>