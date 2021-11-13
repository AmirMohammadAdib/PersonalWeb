// document.querySelector('.sidebar').style = "display: none";


document.querySelector('.window').addEventListener("click", function() {

    let side = document.querySelector('.sidebar');

    side.style = "visibility: inherit;";



})


document.querySelector('.exet').addEventListener("click", function() {


    let side2 = document.querySelector('.sidebar');

    side2.style = "visibility: hidden;";



})


var slides = document.querySelector(".slider-items").children;
var nextslide = document.querySelector(".right-slide");
var preslide = document.querySelector(".left-slide");
var totalslides = slides.length;
var index = 0;
nextslide.onclick = function() {
    next("next");
}
preslide.onclick = function() {
    next("pre");
}

function next(direction) {
    if (direction == "next") {
        index++;
        if (index == totalslides) {
            index = 0;
        }
    } else {
        if (index == 0) {
            index = totalslides - 1;
        } else {
            index--;
        }
    }

    for (var i = 0; i < totalslides; i++) {
        slides[i].classList.remove("active");
    }
    slides[index].classList.add("active");
}


// function up_video() {

//     let video = document.querySelector(".content-video-part");

//     video.style = "display: block";


// }



var click = document.querySelector('.window');
var menu = document.querySelector('.navigation');
var bool = true;

function sum() {
    // if (bool == true) {
    //     document.querySelector('.b').style = "opacity: 0;"
    // } else {

    // }
    // document.querySelector('.navigation').style = ''
}