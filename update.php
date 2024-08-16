<?php
require 'vendor/autoload.php';
use Carbon\Carbon;
include 'db.php';
$id = (int) $_GET['id'];
$post = new Post();
$row = $post->getPostById($id)[0];

if (isset($_POST['update'])) {
    $post->updatePost($id);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="blogs.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center my-6 header">Blog</h1>
        <div>
            <div class="blog">
                <h2><?php echo $row['title']; ?></h2>
                <ul>
                    <li>By: <?php echo $row['author']; ?></li>
                    <li>Created: <?php
                    $created_at = Carbon::create($row['created_at']);
                    echo $created_at->diffForHumans(); ?>
                    </li>
                </ul>
                <p><?php echo $row['content']; ?></p>
                <ul>
                    <li><button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editModal">
                            Edit</button></li>
                    <li><button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            Delete</a></button></li>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit Blog</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="form-group" method="post">
                        <div class="modal-body">
                            <label for="title">Title: </label>
                            <input type="text" id="title" name="title" value="<?php echo $row['title'] ?>"
                                class="form-control">
                            <label for="content">Content: </label>
                            <textarea type="text" id="content" name="content" class="form-control" rows="4"><?php echo $row['content'] ?></textarea>
                            <label for="author">Author Name: </label>
                            <input type="text" id="author" name="author" value="<?php echo $row['author'] ?>"
                                class="form-control">


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" name="update" value="Update Post" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Post?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this post?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <a type="button" class="btn btn-primary" id="delete"
                            href="delete.php?id=<?php echo $row['id']; ?>">Yes</a>
                    </div>
                </div>
            </div>
        </div>
        <a href="index.php" class="btn btn-warning my-4">Back</a>
    </div>
</body>

</html>