<?php

require('common.php');
/** @var PDO $dbh Database Connection */

if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // PHP $_FILES error readable references
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        // Allowed MIME types
        $allowedMIME = array(
            'image/jpeg',
            'image/png',
            'image/gif'
        );

        if (
            !empty($_POST['name']) &&
            is_string($_POST['name']) &&
            !empty($_POST['price']) &&
            $_POST['price'] > 0 &&
            $_POST['price'] < 9999999.99
        ) {
            // Transaction to allow reversion if error occurs
            $dbh->beginTransaction();

            // Update course
            $course_stmt = $dbh->prepare("UPDATE `course` SET `name` = :name, `price` = :price, `category_id` = :category WHERE `id` = :id");
            if (!$course_stmt->execute([
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'category' => $_POST['category'],
                'id' => $_GET['id']
            ])) {
                $dbh->rollback();  // In case of error, rollback everything
                header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The course cannot be updated. Please try again!'));
                exit();
            }

            $insertedCourseId = $_GET['id'];

            // Process and insert the image file
            // NOTE: file size validation in this demo code is only implemented in Javascript - see /js/scripts.js file for details

            // If no file is uploaded, then no need to process uploaded files

            $count = count($_FILES['image']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['image']['error'][$i] != 4) {
                    // Check if any of the files has error during upload
                    if ($_FILES['image']['error'][$i] != 0) {
                        $dbh->rollback();  // In case of error, rollback everything
                        header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode("File '" . $_FILES['image']['name'][$i] . "' did not upload because: " . $phpFileUploadErrors[$_FILES['image']['error'][$i]]));
                        exit();
                    }

                    // Check if any of the files is in wrong MIME type
                    if (!empty($_FILES['image']['type'][$i]) && !in_array($_FILES['image']['type'][$i], $allowedMIME)) {
                        $dbh->rollback();  // In case of error, rollback everything
                        header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode("The type of file '" . $_FILES['image']['name'][$i] . "' (" . $_FILES['image']['type'][$i] . ") is not allowed"));
                        exit();
                    }

                    // Insert new image to course_images table
                    $image_stmt = $dbh->prepare("INSERT INTO `course_image`(`course_id`, `filePath`) VALUES (:course_id, :filePath)");
                    $new_file_path = uniqid('course_' . $insertedCourseId . '_', true) . "." . pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
                    if ($image_stmt->execute([
                        'course_id' => $insertedCourseId,
                        'filePath' => $new_file_path
                    ])) {
                        // Finally, move images to its final place
                        if (!move_uploaded_file($_FILES['image']['tmp_name'][$i], "course_images" . DIRECTORY_SEPARATOR . $new_file_path)) {
                            $dbh->rollback();  // In case of error, rollback everything
                            header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Failed to save image files to the filesystem'));
                            exit();
                        }
                    } else {
                        $dbh->rollback();  // In case of error, rollback everything
                        header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The course cannot be updated. Please try again!'));
                        exit();
                    }
                }
            }
            $dbh->commit();
            header("Location: courses.php");
        } else {
            header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
        }
        exit();
    }
} else {
    // Unavailable if there is no id input
    header("Location: courses.php");
}

include('home.html');
?>

<h1>Edit Course #<?= $_GET['id'] ?></h1>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php }
    $stmt = $dbh->prepare("SELECT * FROM `course` WHERE `id`=?");
    if ($stmt->execute([$_GET['id']])) {
        if ($stmt->rowCount() > 0) {
            $initial = $stmt->fetchObject(); ?>
            <form method="post" enctype="multipart/form-data">
                <div>
                    <label for="name">New Name: </label>
                    <input type="text" id="name" name="name" maxlength="64" required value="<?= $initial->name ?>"/>
                </div>
                <div>
                    <label for="price">New Price: </label>
                    <input type="number" id="price" name="price" min="0" max="999999999" required value="<?= $initial->price ?>"/>
                </div>
                <div>
                    <label for="category">New Category: </label>
                    <select id="category" name="category">
                        <?php
                        $categories = $dbh->prepare("SELECT * FROM `CATEGORY`");
                        $categories->execute();
                        while ($cat = $categories->fetchObject()) {
                            if ($cat->parent_id) {
                                $parent = $dbh->prepare("SELECT * FROM `CATEGORY` WHERE `id` = ?");
                                $parent->execute([$cat->parent_id]);
                                if ($cat->id == $initial->category_id ) {
                                ?>
                                    <option value="<?= $cat->id ?>" selected ><?= $parent->fetchObject()->name ." - ". $cat->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $cat->id ?>" ><?= $parent->fetchObject()->name ." - ". $cat->name ?></option>
                            <?php }
                            } else {
                                if ($cat->id == $initial->category_id ) {
                                ?>?>
                                    <option value="<?= $cat->id ?>" selected ><?= $cat->name ?></option>
                                <?php } else { ?>
                                    <option value="<?= $cat->id ?>" ><?= $cat->name ?></option>
                                <?php }
                            }
                        }?>
                    </select>
                </div>
                <div>
                    <label for="image">Add More Images: </label>
                    <input type="file" id="image" name="image[]" onchange="image_checker(event)" multiple="multiple"/>
                </div>
                <div>
                    <input type="submit" value="Update"/>
                </div>
                <div>
                    <a href="courses.php">Cancel and back to list</a>
                </div>
            </form>
        <?php } else {
            header("Location: courses.php");
        }
    } else {
        $dbh->rollback();  // In case of error, rollback everything
        header("Location: course_edit.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The course cannot be updated. Please try again!'));
        exit();
    }
    ?>
</div>
<script>
    // A callback function as event listener in input attribute (so we can do some validation)
    function image_checker(event) {
        let file_is_valid = true;

        // get the file uploaded in JS
        let file = event.target.files[0];

        // Test file size
        let size = file.size;
        if (size > 2000000) {
            file_is_valid = false;
            // When file is more than 2MB in size
            event.target.setCustomValidity("File is too big! The size must be smaller than 2MB");
        }

        // Test file type
        let filetype = file.type;
        if (!(['image/jpeg', 'image/png', 'image/gif'].includes(filetype))) {
            file_is_valid = false;
            // When file type is not in the list
            event.target.setCustomValidity("File must be JPEG, PNG or GIF formatted images");
        }

        if (file_is_valid) {
            // Clear the error message if there's no error
            event.target.setCustomValidity("");
        }
    }
</script>