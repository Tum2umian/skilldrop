document.addEventListener("DOMContentLoaded", function () {
    const profileForm = document.getElementById("editProfileForm");
    const profileImageForm = document.getElementById("profileImageForm");
    const messageDiv = document.getElementById("profileMessage");

    // **🖼️ Profile Image Upload**
    document.getElementById("profileImageInput").addEventListener("change", function () {
        let formData = new FormData(profileImageForm);

        fetch("../ajax/upload-profile-image.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById("profilePicture").src = "../" + data.image;
                messageDiv.textContent = "Profile image updated!";
                messageDiv.className = "success-message";
            } else {
                messageDiv.textContent = data.message;
                messageDiv.className = "error-message";
            }
        })
        .catch(error => console.error("Error:", error));
    });

    // **📝 Profile Updates (Name, Email, Phone, Location, Password)**
    profileForm.addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(profileForm);

        fetch("../ajax/update-profile.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            messageDiv.textContent = data.message;
            messageDiv.className = data.status === "success" ? "success-message" : "error-message";
        })
        .catch(error => console.error("Error:", error));
    });
});
