<?php
require_once("home.html");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['subject']) && !empty($_POST['body'])) {
        $to = "contact_littledreamermusic@example.com";
        $message = "Customer Name: " . $_POST['name'] . "\n"
            . "Phone Number: " . $_POST['phone'] . "\n\n"
            . "Message:\n" . $_POST['body'];
        $headers = "From: " . $_POST['email'];
        if (mail($to, $_POST['subject'], $_POST['body'], $headers)) {
            header("Location: contact.php?&error=" . urlencode('Email sent successfully!'));
        } else {
            header("Location: contact.php?&error=" . urlencode('Cannot send email at the moment. Please try again!'));
        }
    }
    else {
        header("Location: contact.php?&error=" . urlencode('Please complete all fields in the form!'));
    }
}
?>

<h1>Contact Us</h1>
<form method="post">
    <?php if (!empty($_GET['error'])) { ?>
        <p class="error"><?= $_GET['error'] ?></p>
    <?php } ?>
    <p>
        <input type="text" name="name" placeholder="Name" required class="email-form-control">
    </p>
    <p>
        <input type="text" name="email" placeholder="Email" required class="email-form-control">
    </p>
    <p>
        <input type="text" name="phone" placeholder="Phone Number" required class="email-form-control">
    </p>
    <p>
        <input type="text" name="subject" placeholder="Enquiry Subject" required class="email-form-control">
    </p>
    <p>
        <textarea name="body" placeholder="Write enquiry message here" required class="email-form-control"></textarea>
    </p>
    <p>
        <button type="submit">Send Enquiry</button>
    </p>
</form>