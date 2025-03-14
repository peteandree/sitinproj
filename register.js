document.getElementById('registerForm').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent form submission

  // Reset previous error messages
  const errorMessages = document.querySelectorAll('.error-message');
  errorMessages.forEach(error => error.textContent = '');

  // Grab values from the input fields
  const idNo = document.getElementById('idNo').value;
  const lastName = document.getElementById('lastName').value;
  const firstName = document.getElementById('firstName').value;
  const middleName = document.getElementById('middleName').value;
  const course = document.getElementById('course').value;
  const email = document.getElementById('email').value;
  const userName = document.getElementById('userName').value;
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirmPassword').value;

  let valid = true;

  // Validate ID No. (Only integers allowed)
  if (!idNo) {
    document.getElementById('idError').textContent = 'ID No. is required.';
    valid = false;
  } else if (!/^\d+$/.test(idNo)) {  // Check if the ID No is numeric
    document.getElementById('idError').textContent = 'ID No. must be an integer (only numbers are allowed).';
    valid = false;
  }

  // Validate Lastname
  if (!lastname) {
    document.getElementById('lastNameError').textContent = 'Lastname is required.';
    valid = false;
  }

  // Validate Firstname
  if (!firstname) {
    document.getElementById('firstNameError').textContent = 'Firstname is required.';
    valid = false;
  }

  // Validate Course
  if (!course) {
    document.getElementById('courseError').textContent = 'Course is required.';
    valid = false;
  }

  // Validate Email
  if (!email) {
    document.getElementById('emailError').textContent = 'Email is required.';
    valid = false;
  } else if (!/\S+@\S+\.\S+/.test(email)) {
    document.getElementById('emailError').textContent = 'Please enter a valid email address.';
    valid = false;
  }

  // Validate Username
  if (!username) {
    document.getElementById('userNameError').textContent = 'Username is required.';
    valid = false;
  }

  // Validate Password
  if (!password) {
    document.getElementById('passwordError').textContent = 'Password is required.';
    valid = false;
  } else if (password.length < 6) {
    document.getElementById('passwordError').textContent = 'Password must be at least 6 characters.';
    valid = false;
  }

  // Validate Confirm Password
  if (!confirmPassword) {
    document.getElementById('confirmPasswordError').textContent = 'Please confirm your password.';
    valid = false;
  } else if (password !== confirmPassword) {
    document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
    valid = false;
  }

  // If all fields are valid, submit the form (this is where you can proceed to make a request)
  if (valid) {
    // Submit the form or send the data via AJAX
    alert('Registration successful!');
    // For example, if you're submitting via AJAX:
    // fetch('register.php', { method: 'POST', body: JSON.stringify({ ... }) })
  }
});
