<?php
require ('PHP/ConnexionBD.php');
require ('PHP/Post.php');
session_start();
if (!$_SESSION['login']) {
    header('Location: ../login.php');
}
$bd=ConnexionBD::getInstance();
$user=$_GET['login'];
$query = "SELECT id,image,message,title,time_date,likes FROM posts WHERE username='$user' ORDER BY time_date DESC";
$posts = $bd->query($query)->fetchAll(PDO::FETCH_CLASS,'Post');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">



    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/Posts.css">

    <title>Posts</title>

</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top bg-primary" style="padding-bottom: 1px; border-radius:0px;" color-on-scroll="400">
    <div class="container">
        <div class="navbar-translate"><a class="navbar-brand pull-left" style="margin-top:5px;" href="<?php echo 'monprofil.php?login='.$_SESSION['login'] ?>" rel="tooltip">Profile</a>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul style="list-style-type: none; margin-top: 5px;" class="navbar-nav pull-right">

                <li class="nav-item"><a class="nav-link smooth-scroll" href="<?php echo 'Posts.php?login='.$_SESSION['login'] ?>">Blog</a></li>
                <li class="nav-item"><a class="nav-link smooth-scroll" href="<?php echo 'acceuil.php?login='.$_SESSION['login'] ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link smooth-scroll" href="logout.php">Logout</a></li>

            </ul>
        </div>
    </div>
</nav>
<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" style="position: absolute;">&times;</span>
        <h3 style="position: relative; margin-left: 50px;">Create a new post</h3>
        <form method="post" role="form" enctype="multipart/form-data" action="PHP/CreatePost.php" >
            <div class="row">
                    <label style="margin-left: 11px; margin-bottom: 10px;" >Add Image</label>
                  <input style="margin-bottom: 10px;"  type="file" name="image" class="col-2" id="im" value="Add Image">
            </div>
            <div class="row">
                <label style="margin-left: 12px; margin-bottom: 10px;">Title</label>
                <input  style="margin-bottom: 10px; margin-left: 60px;" type="text" class="col-6" name="title" id="title" title="title">
            </div>
            <div class="row">
                <label style="margin-left: 12px;">Message</label>
                <textarea style="margin-bottom: 10px; margin-left: 27px;" rows="4" cols="90" name="message" id="message" style="width: 100%" title="message" ></textarea>
            </div>

            <input  class="btn btn-success col-3" style="margin-left: 85px;" name="submit" id="submitpost" type="submit" value="Confirm" >
        </form>

    </div>

</div>

<div class="blog">
    <div class="container">

        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 text-center">
                <h2>Blog Posts</h2>

                <button <?php if(($_SESSION['login'])!=$user) {?> style="display: none" <?php } ?> type="button" class="btn btn-success" id="modalbtn">
                    New Post</button> <!--set condition if connected user has this blog-->
                <br>
            </div>
        </div>

        <div class="row">

            <?php  foreach ($posts as $post): ?>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" data-aos="fade-left" style="padding-left: 0px;">
                    <div class="card text-center" style="padding-left: 0px; padding-right: 0px;">
                        <img id="img" width="match-parent" height="200" style="margin-top=20px;" src="docs\im<?php echo $post->getImage();?>"alt="img" >
                        <div class="card-block">
                            <h4 class="card-title"><?php echo $post->getTitle(); ?></h4>
                            <p class="card-text" style="height: 100px; overflow: hidden;"><?php  echo $post->getMessage(); ?></p>
                            <a class="btn btn-default" href="blogpost.php?id=<?php echo $post->getId();?>">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    </div>
</div>
<script src="js/Modal.js"></script>
<script>
    $(document).ready(function() {
        $('#submit').click(function () {
            var post_title = $('#title').val();
            if (post_title == '') {
                alert("Please add the Title");
                return false
            }
            var extension = $('#im').val().split('.').pop().toLowerCase();
            if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) ==  -1 ) {
                alert('Invalid image File');
                $('#im').val('');
                return false
            }
        });
    });
</script>

<script src="js/core/jquery.3.2.1.min.js"></script>
<script src="js/core/bootstrap.min.js"></script>
</body>
</html>