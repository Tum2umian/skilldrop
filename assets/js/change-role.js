document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("roleModal");
    const closeModal = document.querySelector(".close-btn");

    document.querySelectorAll(".role-change-btn").forEach(button => {
        button.addEventListener("click", function () {
            // Get user ID & current role from button attributes
            const userId = this.getAttribute("data-user-id");
            const currentRole = this.getAttribute("data-current-role");

            // Set values in modal form
            document.getElementById("modalUserId").value = userId;
            document.getElementById("newRole").value = currentRole;

            // Show the modal
            modal.style.display = "flex";
        });
    });

    // Close modal when close button is clicked
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close modal if clicking outside the modal content
    window.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
