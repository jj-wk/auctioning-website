<!-- If you like, you can put a page footer (something that should show up at
     the bottom of every page, such as helpful links, layout, etc.) here. -->

<script>
     const form = document.getElementById("myForm");
      const password = form.password;
      const confirmPassword = form.passwordConfirmation;
      const feedback = document.getElementById("message");
      let isPasswordMatch = false;

      passwordConfirmation.addEventListener("input", () => {
        if (password.value != passwordConfirmation.value) {
          feedback.style.color = 'red';
          feedback.innerHTML = "Password did not match.";
          isPasswordMatch = false;
        } else {
          feedback.style.color = 'green';
          feedback.innerHTML = "Match";
          isPasswordMatch = true;
        }
      });

</script>


<!-- Bootstrap core JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>