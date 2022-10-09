<?php
require_once('common.php');
/** @var PDO $dbh Database Connection */

$categories = $dbh->prepare("SELECT * FROM `category`");

include('home.html');
?>
<h1>CATEGORIES</h1>
<div>
    <a href="category_add.php">Add new category</a>
    <form method="post" action="category_delete.php" id="categories-delete-form">
        <input type="submit" value="Delete selected categories">
        <?php if ($categories->execute() && $categories->rowCount() > 0) { ?>
            <table>
                <thead>
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
                        <td class="table-cell-left"><a href="category_details.php?id=<?= $category->id ?>"><?= $category->name ?></a></td>
                        <?php
                        $parent = $dbh->prepare("SELECT `name` FROM `CATEGORY` WHERE `id`=?");
                        $parent->execute([$category->parent_id]);
                        ?>
                        <td class="table-cell-left"><?= $parent->rowCount() > 0? $parent->fetchObject()->name : "None" ?></td>
                        <td>
                            <a href="category_edit.php?id=<?= $category->id ?>">Edit</a>
                            <button type="submit" name="category_ids[]" value="<?= $category->id ?>">Delete</button>
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