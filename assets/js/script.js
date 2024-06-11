console.log("ok");
let rest = document.body;

rest.addEventListener("click", e => {
  console.log(e.target)
  if (e.target.classList.contains('.header')) {
    console.log('header');
  }
});