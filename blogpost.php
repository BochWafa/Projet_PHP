<?php

require ('PHP/ConnexionBD.php');
require ('PHP/Post.php');
require  ('PHP/Comment.php');
session_start();
if (!$_SESSION['login']) {
    header('Location: ../login.php');
}
$bd=ConnexionBD::getInstance();
$id = $_GET['id'];
//add the comments later
$query = "SELECT username,image,message,title,time_date,likes FROM posts WHERE id='$id'";
$post = $bd->query($query)->fetchAll(PDO::FETCH_CLASS,'Post');
$user = $post[0]->getUsername();
$imq = " SELECT path FROM user WHERE login='$user'";
$image = $bd->query($imq)->fetchColumn();

if (isset($_POST['submit'])) {
    $idc = uniqid();
    $comm = $_POST['comment'];
    $userc = $_SESSION['login'];
    $postid = $id;

    try {

        $query = $bd->prepare("INSERT INTO commentaires (id, username, postid, contenu)
VALUES (:id, :username, :postid, :contenu)");
        $query->bindParam(':id', $idc);
        $query->bindParam(':username', $userc);
        $query->bindParam(':postid', $postid);
        $query->bindParam(':contenu', $comm);
        $query->execute();
        header('Location: blogpost.php?id='.$id);

    }

    catch (PDOException $e) {

        echo $e;
    }
}
$query = "SELECT username,contenu,id,postid FROM commentaires";
$comms = $bd->query($query)->fetchAll(PDO::FETCH_CLASS,'Comment');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blogpost</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link href="css/blogpost.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
    <link  href="http://ironsummitmedia.github.io/startbootstrap-modern-business/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>

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
<div class="container">
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-6"><br><br><br>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="pull-left media">
                        <img class="img-responsive" src="docs\<?php echo $image; ?>" height="70" width="70" alt="...">
                    </div>
                    <div class="pull-right"><br>
                        <b><a href="monprofil.php?login=<?php echo $post[0]->getUsername();?>"><?php echo $post[0]->getUsername(); ?></a></b>
                    </div><br><br><br>
                    <div style="alignment: center">
                        <h4><?php echo $post[0]->getTitle(); ?></h4>
                    </div>
                    <p><?php echo $post[0]->getMessage(); ?>

                        <br><br>
                        <img src="docs\im<?php echo $post[0]->getImage(); ?>" class="img-responsive"><hr>
                    <div class="pull-left">
                        <a  style="text-decoration: none; color: grey;" id="cbutton"><b>Comment</b></a>  
                           </b>
                    </div>



                    </p>
                </div>

            </div>


        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <form method="post" id="formc" name="comments" style="display: none">
            <textarea rows="3" cols="80" style="resize: none; overflow: auto;" name="comment" id="comment">
            </textarea>
            <input class="btn btn-default submit" type="submit" name="submit" id="cmm" value="Confirm" style="border-radius:0px; background-color:grey; margin-bottom: 6px; ">
            </form>

        </div>
    </div>
    <?php  foreach ($comms as $comm): ?>
    <div class="row">
        <div class="col-md-2">
        </div>
        <div class="col-md-6">
            <div class="panel panel-white post panel-default">
                <div class="post-heading">
                    <div class="pull-left meta">
                        <div class="title h5">
                            <a style="color: #0c5460;" href="monprofil.php?login=<?php echo $comm->getUsername() ?>"<b><?php echo $comm->getUsername();?></b></a>
                        </div>
                    </div>
                </div>
                <div class="post-description">
<p>  <?php echo $comm->getContenu();?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<script>
    $("#cbutton").click(function(){
        $("#formc").toggle();
    });
</script>

<script src="js/core/jquery.3.2.1.min.js"></script>
<script src="js/core/bootstrap.min.js"></script>

</body>

</html>