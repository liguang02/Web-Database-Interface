<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$categories = $dbh->prepare("SELECT * FROM `category`");

include('home.html');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<br><hr>
<h1 class="display-3 text-center"><ins>CATEGORIES</ins></h1>
<div>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item" style="padding-left:5.5in">
                <a class="btn btn-success" href="category_add.php">Add new category</a>

            </li>
            <li class="nav-item" style="padding-left:0.2in">
                <form method="post" action="category_delete.php" id="categories-delete-form">
                    <input class="btn btn-danger"  type="submit" value="Delete selected categories">
            </li>
        </ul>
    </nav>

        <?php if ($categories->execute() && $categories->rowCount() > 0) { ?>
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Select</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Parent Category</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($category = $categories->fetchObject()) { ?>
                    <tr>
                        <td><input type="checkbox" name="category_ids[]" value="<?= $category->id ?>"/></td>
                        <td><?= $category->id ?></td>
                        <td><?= $category->name ?> </td>
                        <?php
                        $parent = $dbh->prepare("SELECT `name` FROM `CATEGORY` WHERE `id`=?");
                        $parent->execute([$category->parent_id]);
                        ?>
                        <td class="table-cell-left"><?= $parent->rowCount() > 0? $parent->fetchObject()->name : "None" ?></td>
                        <td>
                            <a class="btn btn-info" href="category_details.php?id=<?= $category->id ?>">Details</a>
                            <a class="btn btn-secondary" href="category_edit.php?id=<?= $category->id ?>">Edit</a>
                            <button class="btn btn-danger" type="submit" name="category_ids[]" value="<?= $category->id ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else {?>
            <p>There's no data in categories</p>
        <?php } ?>
    </form>
</div>
</body>
</html>