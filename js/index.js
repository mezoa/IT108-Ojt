document.querySelector("#register-btn").addEventListener("click", () => {
  document.querySelector("#register-form").classList.toggle("visually-hidden");
  document.querySelector("#login-form").classList.toggle("visually-hidden");
});

document.querySelector("#login-btn").addEventListener("click", () => {
  document.querySelector("#register-form").classList.toggle("visually-hidden");
  document.querySelector("#login-form").classList.toggle("visually-hidden");
});
