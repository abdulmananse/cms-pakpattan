"use strict";
document.addEventListener("DOMContentLoaded", function () {
    

    // layout types
    var layouttype = document.querySelectorAll(".layout-type > a");
    for (var h = 0; h < layouttype.length; h++) {
        var c = layouttype[h];
        c.addEventListener('click', function (event) {
            document.querySelector(".layout-type > a.active").classList.remove("active");
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            targetElement.classList.add("active");
            var temp = targetElement.getAttribute('data-value');
            document.querySelector('head').insertAdjacentHTML("beforeend", '<link rel="stylesheet" class="layout-css" href="">');
            if (temp == "menu-dark") {
                removeClassByPrefix(document.querySelector(".pcoded-navbar"), 'menu-');
                document.querySelector(".pcoded-navbar").classList.remove("navbar-dark");
            }
            if (temp == "menu-light") {
                removeClassByPrefix(document.querySelector(".pcoded-navbar"), 'menu-');
                document.querySelector(".pcoded-navbar").classList.remove("navbar-dark");
                document.querySelector(".pcoded-navbar").classList.add(temp);
            }
            if (temp == "reset") {
                location.reload();
            }
            if (temp == "dark") {
                removeClassByPrefix(document.querySelector(".pcoded-navbar"), 'menu-');
                document.querySelector(".pcoded-navbar").classList.remove("navbar-dark");
                document.querySelector(".layout-css").setAttribute('href', '../assets/css/layout-dark.css');
            } else {
                document.querySelector(".layout-css").setAttribute('href', '');
            }
        });
    }
    // Header Color
    var headercolor = document.querySelectorAll(".header-color > a");
    for (var h = 0; h < headercolor.length; h++) {
        var c = headercolor[h];
        c.addEventListener('click', function (event) {
            document.querySelector(".header-color > a.active").classList.remove("active");
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            targetElement.classList.add("active");
            var temp = targetElement.getAttribute('data-value');
            if (temp == "header-default") {
                removeClassByPrefix(document.querySelector(".pcoded-header"), 'header-');
            } else {
                removeClassByPrefix(document.querySelector(".pcoded-header"), 'header-');
                document.querySelector(".pcoded-header").classList.add(temp);
            }
        });
    }
   
    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }
    // ==================    Menu Customizer End   =============
    // =========================================================
});