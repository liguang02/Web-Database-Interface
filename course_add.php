<?php

require('common.php');
/** @var PDO $dbh Database Connection */

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

        // Add the new course and get its primary key
        $course_stmt = $dbh->prepare("INSERT INTO `course` (`name`, `price`, `category_id`) VALUE (:name, :price, :category)");
        if (!$course_stmt->execute([
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'category' => $_POST['category']
        ])) {
            $dbh->rollback();  // In case of error, rollback everything
            header("Location: course_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The new course cannot be added. Please try again!'));
            exit();
        }

        $insertedCourseId = $dbh->lastInsertId();

        // Process and insert the image file
        // NOTE: file size validation in this demo code is only implemented in Javascript - see /js/scripts.js file for details

        // If no file is uploaded, then no need to process uploaded files

        $count = count($_FILES['image']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['image']['error'][$i] != 4) {
                // Check if any of the files has error during upload
                if ($_FILES['image']['error'][$i] != 0) {
                    $dbh->rollback();  // In case of error, rollback everything
                    header("Location: course_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode("File '" . $_FILES['image']['name'][$i] . "' did not upload because: " . $phpFileUploadErrors[$_FILES['image']['error'][$i]]));
                    exit();
                }

                // Check if any of the files is in wrong MIME type
                if (!empty($_FILES['image']['type'][$i]) && !in_array($_FILES['image']['type'][$i], $allowedMIME)) {
                    $dbh->rollback();  // In case of error, rollback everything
                    header("Location: course_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode("The type of file '" . $_FILES['image']['name'][$i] . "' (" . $_FILES['image']['type'][$i] . ") is not allowed"));
                    exit();
                }

                // Insert new course image to course_images table
                $image_stmt = $dbh->prepare("INSERT INTO `course_image`(`course_id`, `filePath`) VALUES (:course_id, :filePath)");
                $new_file_path = uniqid('course_' . $insertedCourseId . '_', true) . "." . pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
                if ($image_stmt->execute([
                    'course_id' => $insertedCourseId,
                    'filePath' => $new_file_path
                ])) {
                    // Finally, move images to its final place
                    if (!move_uploaded_file($_FILES['image']['tmp_name'][$i], "course_images" . DIRECTORY_SEPARATOR . $new_file_path)) {
                        $dbh->rollback();  // In case of error, rollback everything
                        header("Location: course_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Failed to save image files to the filesystem'));
                        exit();
                    }
                } else {
                    $dbh->rollback();  // In case of error, rollback everything
                    header("Location: course_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('The new course cannot be added. Please try again!'));
                    exit();
                }
            }
        }
        $dbh->commit();
        header("Location: courses.php");
    } else {
        header("Location: course_add.php?" . $_SERVER['QUERY_STRING'] . "&error=" . urlencode('Make sure the form is valid before send!'));
    }
     exit();
}

require_once('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>Add New Course</ins></h1><br>
<div>
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <div class="section text-center">
        <div>
            <label for="name">Name: </label>
            <input type="text" id="name" name="name" maxlength="64" required/><br><br>
        </div>
        <div>
            <label for="price">Price: </label>
            <input type="number" id="price" name="price" min="0" max="999999999" required/><br><br>
        </div>
        <div>
            <label for="category">Category: </label>
            <select id="category" name="category">
                <?php
                $categories = $dbh->prepare("SELECT * FROM `CATEGORY`");
                $categories->execute();
                while ($cat = $categories->fetchObject()) {
                    if ($cat->parent_id) {
                        $parent = $dbh->prepare("SELECT * FROM `CATEGORY` WHERE `id` = ?");
                        $parent->execute([$cat->parent_id]);
                ?>
                        <option value="<?= $cat->id ?>"><?= $parent->fetchObject()->name ." - ". $cat->name ?></option>
                    <?php } else { ?>
                        <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                    <?php }
                }?>
            </select><br><br>
        </div>
        <div>
            <label for="image">Images: </label>
            <input type="file" id="image" name="image[]" onchange="image_checker(event)" multiple="multiple"/><br><br>
        </div>
            <input class="btn btn-success" type="submit" value="Submit"/>
            <a class="btn btn-danger" href="courses.php">Cancel and back to list</a>
        </div>
    </form>
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
