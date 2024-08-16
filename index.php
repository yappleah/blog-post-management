<!DOCTYPE html>
<?php
require 'vendor/autoload.php';
use Carbon\Carbon;

include 'db.php';
$db = new Model();

$rows = $db->query('SELECT * FROM blogs')->fetchAll();

$post = new Post();
if (isset($_POST['send'])) {
    $post->createPost();
}


?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="blogs.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center my-6 header">Blog Posts</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newModal">
            Add Blog</button>
        <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="newModalLabel">New Blog</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="form-group" method="post">
                        <div class="modal-body">
                            <label for="title">Title: </label>
                            <input type="text" id="title" name="title" value="" class="form-control">
                            <label for="content">Content: </label>
                            <textarea type="text" id="content" name="content" value="" class="form-control" rows="4"></textarea>
                            <label for="author">Author Name: </label>
                            <input type="text" id="author" name="author" value="" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" name="send" value="Add Post" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>
            <?php foreach ($rows as $row): ?>
                <div class="blog">
                    <h2><?php echo $row['title']; ?></h2>
                    <ul>
                        <li>By: <?php echo $row['author']; ?></li>
                        <li>Created: <?php 
                            $created_at = Carbon::create($row['created_at']);
                        echo $created_at->diffForHumans(); ?></li>
                    </ul>
                    <p><?php 
                        $string = $row['content'];
                        if(strlen($string > 400)) {
                            $string = substr($string, 0, 450).'...';
                        }
                        echo $string;
                    ?></p>
                    <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-info offset-md-10">See more</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>