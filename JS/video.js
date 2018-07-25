/* webcam and video functionality */
navigator.getUserMedia = navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.oGetUserMedia ||
    navigator.msGetUserMedia;

if(navigator.getUserMedia) {
    const mediaSource = new MediaSource();
    var videoPlaying = false;
    var video = document.getElementById('video');
    var img = document.getElementById('default_image');
    navigator.getUserMedia({video: true}, streamWebCam, throwError);
}

function streamWebCam(mediaSource) {
    video.srcObject = mediaSource;
    video.play();
    videoPlaying = true;
}

function throwError(e) {
    alert(e.name);
}