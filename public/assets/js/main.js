var slideIndex = 0;
showSlides();

function showSlides() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    if (slideIndex - 1 in slides) {
        slides[slideIndex-1].style.display = "block";
    }
    setTimeout(showSlides, 4000); // Change image every 2 seconds
}

$(function() {
    $('#datepicker').datepicker();
});
