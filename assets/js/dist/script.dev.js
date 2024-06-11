"use strict";

console.log("ok");
var rest = document.body;
rest.addEventListener("click", function (e) {
  console.log(e.target);

  if (e.target.classList.contains('.header')) {
    console.log('header');
  }
});