// auth.js

// Example: Simple email validation function
function validateEmail(email) {
    const re = /\S+@\S+\.\S+/;
    return re.test(email);
  }
  
  // When the DOM is fully loaded, add event listeners to authentication forms
  document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
      loginForm.addEventListener("submit", function (e) {
        const emailphone = document.getElementById("emailphone").value;
        if (!emailphone) {
          e.preventDefault();
          alert("Please enter your email or phone.");
        }
        // You can add more client-side validations here.
      });
    }
  });
  