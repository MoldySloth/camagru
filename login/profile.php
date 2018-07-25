<?php

require_once 'core/init.php';

//check if we don't have a user name supplied
if(!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {

    // check if this user actually exists
    $user = new User($username);

    if(!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }

    $gallery = new Image();
    $gallery->setPath( '../resources/uploads/' . $data->id);

    $images = $gallery->getUserGallery($data->id);
    $n      = count( $images );

    ?>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="../css/styling.css">
        <link href='https://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="topnav" id="myTopnav">
            <a href="index.php"><img src="../resources/logo/camagru_logo_173x50.png"></a>
            <?php
            if($user->hasPermission('moderator')) {
                ?> <a href="admin_profile.php">Admin</a> <?php
            } else {
                ?>
                <a href="profile.php?user=<?php echo escape($user->data()->username); ?>" class="active">Profile</a>
                <a href="capture.php?user=<?php echo escape($user->data()->username); ?>">Capture</a>
                <?php
            }
            ?>
            <a href="logout.php">Logout</a>
            <a href="javascript:void(0);" style="font-size:15px; height: 100%;" class="icon" onclick="myFunction()">&#9776;</a>
        </div>
        <div class="main_content">
            <div class="row">
                <div class="column side" style="background-color: #aaa">
                    <h2>Profile</h2>
                    <p>Your basic info here</p>
                    <img class="profile_image" src="<?php echo escape($user->data()->profile_img); ?>">
                    <h1><?php echo escape($data->username); ?></h1>
                    <h3>Full name: <?php echo escape($data->name); ?></h3>
                    <div class="link">
                        <a href="update.php" class="link">Update information</a>
                    </div>
                    <div class="link">
                        <a href="change_passwd.php" class="link">Change password</a>
                    </div>
                </div>
                <div class="column middle" style="background-color: #bbb">
                    <h2>Your gallery</h2>
                    <p>All your photos can be viewed from here</p>
	                <?php if($images): $index = 0;?>
                        <div id="gallery" class="gallery cf">
			                <?php foreach($images as $image): $index++; ?>
                            <div class="user_gallery_box">
                                <div class="user_gallery_item" id="<?php echo escape($image->id); ?>" onclick="openPopup(this.id);">
                                    <img id="gallery_image" class="user_gallery_image" src="<?php echo $image->image_url; ?>" alt="<?php escape($username); ?>_image_<?php echo $index; ?>">
                                    <div class="user_gallery_overlay">
                                        <img src="<?php echo $image->image_overlay; ?>">
                                    </div>
                                </div>

                                <!-- like button -->
                                <form action="" method="POST" class="form-like">
                                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                    <input type="hidden" name="image_id" value="<?php echo escape($image->id); ?>">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-like" name='like' value="<?php echo escape($image->id); ?>" onclick="" disabled>
                                            <img src="../resources/icons/heart-white.svg" alt="like-button">
                                            <span><?php echo escape($image->likes); ?></span><!--number of likes -->
                                        </button>
                                    </div>
                                </form>

                                <!-- The image popup with image and info and comments feed -->
                                <div class="popup" id="<?php echo escape($image->id); ?>_popup">
                                    <span class="popup-close cursor" id="<?php echo escape($image->id); ?>" onclick="closePopup(this.id);"></span>
                                    <h2 class="popup-header">This image was taken by <?php
                                        $user_image = new User($image->user_id);
                                        echo escape($user_image->data()->username);
                                        ?></h2>
                                    <!-- Image div -->
                                    <div class="popup-image-box">
                                        <img class="popup-image" src="<?php echo $image->image_url; ?>">
                                        <div class="popup-overlay">
                                            <img src="<?php echo $image->image_overlay; ?>">
                                        </div>
                                    </div>

                                    <!-- Comments div -->
                                    <?php
                                    $comments = new Comment();
                                    $image_comments = $comments->findImageComments($image->id);
                                    ?>
                                    <div class="popup-comments-feed">
                                        <!-- All comments for the image are viewed here -->
                                        <div class="popup-comments">
                                            <?php if($image_comments): ?>
                                                <div class="comments">
                                                    <?php foreach($image_comments as $comment): ?>
                                                        <div class="comment">
                                                            <h5 class="comment-username"><?php
                                                                $user_comment = new User($comment->user_id);
                                                                echo escape($user_comment->data()->username)
                                                                ?></h5>
                                                            <p class="comment-comment"><?php echo escape($comment->comment); ?></p>
                                                            <p class="comment-date"><?php echo date('F d, Y h:ma', strtotime(($comment->created))); ?></p>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <h3>There are no comments available for this images.</h3>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Comment form -->
                                        <form action="" method="POST" class="form-comment">
                                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                                            <input type="hidden" name="image_id" value="<?php echo $image->id; ?>">
                                            <div class="field">
                                                <label class="text-label" for="image_comment">Write your comment comment here...</label>
                                                <textarea name="image_comment" id="<?php echo escape($image->id);?>_comment"></textarea>
                                                <button type="submit" class="btn btn-save" name='comment' id="<?php echo escape($image->id); ?>" onclick="saveComment(this.id, <?php echo escape($image->user_id); ?>);">
                                                    <span>Save comment</span><!--number of likes -->
                                                </button>
                                            </div>
                                        </form> <!-- end of comment form -->
                                    </div>
                                </div>
                            </div>
			                <?php endforeach; ?>
                        </div>
	                <?php else: ?>
                        There are no images.
	                <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="footer">
            <?php
            // check user permissions (admin property value in groups table... pulled from Jason format(javascript object notation, lightweight data transfer format) 1 signifies that they are and 0 that they aren't)
            if($user->hasPermission('moderator')) {
                echo'<p>You are a moderator!</p>';
            }
            ?>
            <p>made by rde-jage &copy; <?php echo date("Y"); ?></p>
        </div>
        <script src="../JS/script.js"></script>
    </body>
    </html>

    <?php
}