
/* top nav responsive functionality */
function myFunction() {
    var x = document.getElementById("myTopnav");

    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function removeFile() {
    var preview = document.getElementById('default_image');
    preview.src = "";
}

/* swopping an image on clicking */
function swopImage(element) {
    document.getElementById(element.id).addEventListener('click', function () {
        var id = element.getAttribute('id');
        var refid = "ref_" + id;
        var ref = document.getElementById(refid);
        var href = ref.getAttribute('href');

        // var selected = element.src;
        var target = document.getElementById('overlay');
        target.setAttribute('src', href);
        /* change class to selected */
        imageSelect(element.id);
    });
}

/* Add selective class to the current image */
function imageSelect(target) {
    var save_button = document.getElementById('save');
    var gallery = document.getElementById('gallery').querySelectorAll('.gallery_item');

    for (var i = 0; i < gallery.length; i++) {
        gallery[i].className = gallery[i].className.replace(' selected', '');
        if (i == target) {
            gallery[i].className += " selected";
            save_button.disabled = false;
        }
    }
}

/* button functionality for category selection */
function filterSelection(c, target) {
    /* Add active class to the current button */
    var btnCategories = document.getElementById('categories');
    var btns = btnCategories.getElementsByClassName('btn');

    for (var n = 0; n < btns.length; n++) {
        if (n == target) {
            btns[n].className +=  " active";
        } else {
            btns[n].className = btns[n].className.replace(' active', '');
        }
    }

    var x, i;
    x = document.getElementsByClassName('gallery_item');
    if (c == "all") c = "";
    //ass the show class (display: block) to the filtered elements and remove the show class from elements not selected
    for (i = 0; i < x.length; i++) {
        removeClass(x[i], "show");
        if (x[i].className.indexOf(c) > -1) addClass(x[i], "show");
    }
}

/* Show filtered elements */
function addClass(element, name) {
    var i, array1, array2;
    array1 = element.className.split(' ');
    array2 = name.split(' ');
    for (i = 0; i < array2.length; i++) {
        if (array1.indexOf(array2[i]) == -1) {
            element.className += ' ' + array2[i];
        }
    }
}

/* Hide elements that are not selected */
function removeClass(element, name) {
    var i, array1, array2;
    array1 = element.className.split(' ');
    array2 = name.split(' ');
    for (i = 0; i < array2.length; i++) {
        while (array1.indexOf(array2[i]) > -1) {
            array1.splice(array1.indexOf(array2[i]), 1);
        }
    }
    element.className = array1.join(" ");
}

/* previewing the uploaded image after validation */
function previewFile() {
    var preview = document.getElementById('default_image');
    var file = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
    }
}

/* Validating the image in the image upload */
function validateImage(){
    var file = document.getElementById('file_select').files[0];
    var t = file.type.split('/').pop().toLowerCase();

    // check that it is a valid image file type
    if (t != 'jpeg' && t != 'jpg' && t != 'png') {
        alert('Please select a valid image file');
        document.getElementById('file_select').value = '';
        return false;
    }

    // check to see that the file isn't bigger that 1mb
    if (file.size > 1024000) {
        alert('Max File Upload size is 1mb only');
        document.getElementById('file_select').value = '';
        return false;
    }
    previewFile();
    return true;
}


/* save the captured image function */
function saveImage() {
    // create saving status on save button
    var saveButton = document.getElementById('save');

    // update button text
    saveButton.innerHTML = 'Saving...';
    saveButton.disabled = true;

    // creating all the variables
    var overlay_image = document.getElementById('overlay');
    var video_image = document.querySelector('video');
    var width = video.clientWidth;
    var height = video.clientHeight;

    var photo_canvas = document.getElementById('canvas_photo');
    var photo_context = photo_canvas.getContext('2d');
    var overlay_url = overlay_image.getAttribute('src');

    if (width && height) {

        photo_canvas.width = width;
        photo_canvas.height = height;

        var fileSelect = document.getElementById('file_select');
        var files = fileSelect.files;

        // Create a new FormData object.
        var formData = new FormData();

        // check if a file was added
        if (files.length > 0) {
            // if file then grab data
            var file = files[0];
            formData.append('file_input', file, file.name);
        } else {
            // use video and draw to canvas element
            photo_context.drawImage(video_image, 0, 0, width, height);
            var photo_data = photo_canvas.toDataURL('image/png');
            var photo_string = JSON.stringify(photo_data);
            formData.append('photo', photo_string);
        }

        // Create the tag from the overlay image
        var tag_string = overlay_image.getAttribute('src');
        var tag_array = tag_string.split('/');
        var tag_name = tag_array[tag_array.length - 1];
        var name = tag_name.split('_');
        var tag = name[1];

        // Append overlay image url to form data
        formData.append('overlay', overlay_url);

        // Append tag to form data
        formData.append('tag', tag);

        // Set up the request.
        var xhr = new XMLHttpRequest();

        // Open the connection.
        xhr.open("POST", "../login/save_image.php", true);

        // Set up a handler for when the request finishes.
        xhr.onload = function () {
            if (xhr.status === 200) {
                // File(s) uploaded
                saveButton.innerHTML = 'Saved';
                alert('Your image was saved!');
                location.reload(true);
            } else {
                alert('An error has occurred, try again!');
            }
        };
        // Send the Data.
        xhr.send(formData);
    } else {
        // clear photo function?
    }
}

/* save the like function */
function saveLike($element) {
    // creating all the variables
    var imageID = $element.value;

    // Create a new FormData object.
    var formData = new FormData();

    // Append image id to form data
    formData.append('image_id', imageID);

    // Set up the request.
    var xhr = new XMLHttpRequest();

    // Open the connection.
    xhr.open("POST", "../login/save_like.php", true);

    // Set up a handler for when the request finishes.
    xhr.onload = function () {
        if (xhr.status === 200) {
            // File(s) uploaded
            alert('Your like was saved!');
            location.reload(true);
        } else {
            alert('An error has occurred, try again!');
        }
    };
    // Send the Data.
    xhr.send(formData);
}

/* save the comment function */
function saveComment($element) {
    // creating all the variables
    var imageID = $element;
    var comment = document.getElementById($element+'_comment');

    // Create a new FormData object.
    var formData = new FormData();

    // Append image comment to form data
    formData.append('image_comment', comment.value);

    // Append image id to form data
    formData.append('image_id', imageID);

    // Set up the request.
    var xhr = new XMLHttpRequest();

    // Set up a handler for when the request finishes.
    xhr.onload = function () {
        if (xhr.status === 200) {
            // File(s) uploaded
            alert('Your comment was saved!');
            // location.reload(true);
        } else {
            alert('An error has occurred, try again!');
        }
    };
    // Send the Data.
    // Open the connection.
    xhr.open("POST", "../login/save_comments.php", true);
    xhr.send(formData);
}

// Open the Modal
function openPopup(id) {
    document.getElementById(id+'_popup').style.display = "block";
}

// Close the Modal
function closePopup(id) {
    document.getElementById(id+'_popup').style.display = "none";
}







