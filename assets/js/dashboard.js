// dashboard.js

// Opens the hidden file input for image upload
function openImageUpload() {
    const input = document.getElementById('profileImageInput');
    if (input) {
      input.click();
    }
  }
  
  // Automatically submits the form after a file is selected
  function submitImageForm() {
    const form = document.getElementById('imageUploadForm');
    if (form) {
      form.submit();
    }
  }
  