<?php

require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<script type="text/javascript">alert("' . Session::flash('home') . '")</script>';
}


// create a new user object
$user = new User();
if ($user->isLoggedIn()) {
    $gallery = new OverlayGallery();
    $gallery->setPath('../resources/overlay_images/');

    $images = $gallery->getOverlayImages();
    $n = count($images);


    ?>

    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="../css/styling.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
<!--    MAIN NAV-->
    <div class="topnav" id="myTopnav">
        <a href="index.php"><img src="../resources/logo/camagru_logo_173x50.png"></a>
        <?php
        if($user->hasPermission('moderator')) {
            ?> <a href="admin_profile.php">Admin</a> <?php
        } else {
            ?>
            <a href="profile.php?user=<?php echo escape($user->data()->username); ?>">Profile</a>
            <a href="capture.php?user=<?php echo escape($user->data()->username); ?>" class="active">Capture</a>
            <?php
        }
        ?>
        <a href="logout.php">Logout</a>
        <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
    </div> <!--     END OF NAV-->

    <!--    MAIN CONTENT-->
    <div class="main_content">
        <div class="row">
            <!--    MAIN CAPTURE DIV-->
            <div class="column left" style="background-color: #bbb">

    <!--            WEBCAM CANVAS-->
                <h1><span class="number">1</span>Use your webcam or upload an image</h1>
                <div class="overlay_booth">
                    <video class="video" id="video" poster="../resources/icons/camera.png"></video>
                    <img class="photo" id="photo" src="">
                    <canvas id="canvas_photo"></canvas>
                    <img class="upload" id="default_image" src="">
                    <div class="overlay_image">
                        <img src="" id="overlay">
    <!--                    <canvas id="canvas_overlay"></canvas>-->
                    </div>
                </div>

    <!--            FILE UPLOAD-->
                <div>
                    <input id="file_select" type="file" onchange="validateImage();" value="false">
                    <button id="refresh" onclick="removeFile();">Refresh</button>
                </div>

    <!--            SAVE IMAGE BUTTON-->
                <h1><span class="number">3</span>Save your image to gallery!</h1><button id="save" onclick="saveImage();" disabled="true">Save</button>
                <p>An overlay image must be selected before you can save</p>
            </div> <!--    MAIN CAPTURE DIV END-->

    <!--        OVERLAY IMAGE GALLERY-->
            <div class="column right" style="background-color: #ccc">
                <h1><span class="number">2</span>Choose an overlay</h1>
                <div id="categories">
                    <button class="btn" onclick="filterSelection('all', 0)">Show all</button>
                    <button class="btn" onclick="filterSelection('frames', 1)">Frames</button>
                    <button class="btn" onclick="filterSelection('love', 2)">Romantic</button>
                    <button class="btn" onclick="filterSelection('hipster', 3)">Hipster</button>
                    <button class="btn" onclick="filterSelection('fun', 4)">Fun</button>
                </div>
                <?php if($images): $index = 0;?>
                <div id="gallery" class="gallery cf">
                    <?php foreach($images as $image): $index++; ?>
                    <div class="gallery_item <?php echo $image['filter']?>">
                        <a id="ref_<?php echo $image['image_id']; ?>" href="<?php echo $image['full']; ?>"></a><img src="<?php echo $image['thumb']; ?>" id="<?php echo $image['image_id']; ?>" onclick="swopImage(this);">
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                    There are no images.
            <?php endif; ?>
            </div> <!--        OVERLAY IMAGE GALLERY END -->
        </div>
    </div><!--        MAIN CONTENT END -->

<!--    FOOTER-->
    <div class="footer">
        <?php
        // check user permissions (admin property value in groups table... pulled from Jason format(javascript object notation, lightweight data transfer format) 1 signifies that they are and 0 that they aren't)
        if($user->hasPermission('moderator')) {
            echo'<p>You are a moderator!</p>';
        }
        ?>
        <p>made by rde-jage &copy; <?php echo date("Y"); ?></p>
    </div> <!--    FOOTER END -->

    <script src="../JS/script.js"></script>
    <script src="../JS/video.js"></script>
    </body>
    </html>
    <?php
} else {
    Redirect::to('index.php');
}