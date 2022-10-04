<?php

/** @var $dbh PDO */
/** @var $db_name string */

include("common.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Add new student</title>

<!--    <meta name="description" content="2022 S2 Lab 7 Exercise">-->
<!--    <meta name="author" content="FIT2104 Web Database Interface">-->

    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Add new client</h1>
<div class="center">
    <?php
    // When a POST form is present (the user submitted a new student record form)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)):
        // Check if any of the POST fields are empty (which shouldn't be!)
        foreach ($_POST as $fieldName => $fieldValue) {
            if (empty($fieldValue)) {
                echo friendlyError("'$fieldName' field is empty. Please fix the issue try again. ");
                echo '<div class="center row"><button onclick="window.history.back()">Back to previous page</button></div>';
                die();
            }
        }

        // Process the update record request (if a POST form is submitted)
        // :columnname is a named placeholder (instead of ? for anonymous placeholder)
        // See https://www.php.net/manual/en/pdo.prepare.php
        $query = "INSERT INTO `STUDENTS` (`firstName`,`surname`,`address`,`phone`,`dob`,`email`,`subscribe`) VALUES (:firstName, :surname, :address, :phone, :dob, :email, :subscribe)";

        $stmt = $dbh->prepare($query);

        // Here we create an array with key-value pairs - if you have noted, this essentially
        // generates the same array as $_POST. Yes, you can directly send $_POST into PDOStatement::execute()
        // and that's exactly what those named placeholders are designed for in the first place
        $parameters = [
            'firstName' => $_POST['firstName'],
            'surname' => $_POST['surname'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'dob' => $_POST['dob'],
            'email' => $_POST['email'],
            'subscribe' => (int)$_POST['subscribe'],
        ];

        if ($stmt->execute($parameters)) {
            // Once an INSERT query is successfully executed, lastInsertId() function will be available
            // to get the primary key of previously inserted record. See https://php.net/manual/en/pdo.lastinsertid.php
            $newRecordId = $dbh->lastInsertId();

            // When no POST form is submitted, get the record from database
            $query = "SELECT * FROM `students` WHERE `id` = ?";
            $stmt = $dbh->prepare($query);
            if ($stmt->execute([$newRecordId])) {
                if ($stmt->rowCount() > 0) {
                    $record = $stmt->fetchObject(); ?>
                    <div class="center row">New student has successfully been added.</div>
                    <form method="post">
                        <div class="aligned-form">
                            <div class="row">
                                <label for="client_id">ID</label>
                                <input type="number" id="id" value="<?= $record->id ?>" disabled/>
                            </div>
                            <div class="row">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" value="<?= $record->firstName ?>" disabled/>
                            </div>
                            <div class="row">
                                <label for="surname">Surname</label>
                                <input type="text" id="surname" value="<?= $record->surname ?>" disabled/>
                            </div>
                            <div class="row">
                                <label for="address">Address</label>
                                <input type="text" id="address" value="<?= $record->address ?>" disabled/>
                            </div>
                            <div class="row">
                                <label for="phone">Phone number</label>
                                <input type="tel" id="phone" pattern="\(0[0-9]\) [0-9]{4} [0-9]{4}" value="<?= $record->phone ?>"  disabled/>
                            </div>
                            <div class="row">
                                <label for="dob">Date of Birth</label>
                                <input type="date" id="dob" value="<?= $record->dob ?>" disabled/>
                            </div>
                            <div class="row">
                                <label for="email">Email</label>
                                <input type="email" id="email" value="<?= $record->email ?>" disabled/>
                            </div>
                            <div class="row">
                                <label for="subscribe">Subscribed?</label>
                                <?php
                                if ($record->subscribe==1) {
                                    $subscribed = "Yes";
                                }else{
                                    $subscribed = "No";
                                }
                                ?>
                                <input type="text" id="subscribe" value="<?= $subscribed ?>" disabled/>
                            </div>
                        </div>
                    </form>
                    <div class="center row">
                        <a href="students.php">Back to the student list</a>
                    </div>
                <?php } else {
                    echo "Weird, the new client just added has mysteriously disappeared!? ";
                    echo '<div class="center row"><a href="students.php">Back to the student list</a></div>';
                }
            } else {
                die(friendlyError($stmt->errorInfo()[2]));
            }
        } else {
            echo friendlyError($stmt->errorInfo()[2]);
            echo '<div class="center row"><button onclick="window.history.back()">Back to previous page</button></div>';
            die();
        }
    else:
        // If the user is just being redirected to the page, we should present a form for the user to fill

        // This part is ONLY to retrieve the target table's next primary key increment
        $query = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME='students'";
        $stmt = $dbh->prepare($query);
        $nextId = ($stmt->execute() || $stmt->rowCount() > 0) ? $stmt->fetchObject()->AUTO_INCREMENT : "Not available";
        ?>

        <!-- And here's the "add new client" form -->
        <form method="post">
            <div class="aligned-form">
                <div class="row">
                    <label for="id">ID</label>
                    <input type="text" id="id" value="<?= $nextId ?>" disabled/>
                </div>
                <div class="row">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName"/>
                </div>
                <div class="row">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname"/>
                </div>
                <div class="row">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address"/>
                </div>
                <div class="row">
                    <label for="phone">Contact</label>
                    <input type="tel" id="phone" pattern="\(0[0-9]\) [0-9]{4} [0-9]{4}" name="phone"/>
                </div>
                <div class="row">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob"/>
                </div>
                <div class="row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"/>
                </div>
                <div class="row">
                    <label for="subscribe">Subscribed?</label>
                    <select name="subscribe" id="subscribe">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
<!--                    <input type="number" id="subscribe" name="subscribe" min="0" max="1">-->
                </div>
            </div>
            <div class="row center">
                <input type="submit" value="Add"/>
            </div>
            <div class="row center">
                <a href="students.php">Cancel and back to homepage</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>