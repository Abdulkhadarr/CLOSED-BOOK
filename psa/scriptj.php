function generatePassword() {
  var length = 8,
      charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+~`|}{[]:;?><,./-=",
      password = "";
  
  // Generate at least one of each type of character
  password += getRandomChar("abcdefghijklmnopqrstuvwxyz");
  password += getRandomChar("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
  password += getRandomChar("0123456789");
  password += getRandomChar("!@#$%^&*()_+~`|}{[]:;?><,./-=");
  
  // Generate the remaining characters randomly
  for (var i = 4; i < length; i++) {
    password += getRandomChar(charset);
  }
  
  return password;
}

function getRandomChar(charset) {
  return charset.charAt(Math.floor(Math.random() * charset.length));
}
