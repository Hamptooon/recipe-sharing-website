var prevScrollPos = window.pageYOffset;

window.onscroll = function () {
    var currentScrollPos = window.pageYOffset;

    if (prevScrollPos > currentScrollPos) {
        document.querySelector("header").style.top = "0";
    } else {
        document.querySelector("header").style.top = "-85px";
    }

    prevScrollPos = currentScrollPos;
};