document.addEventListener("DOMContentLoaded", function () {
    const serviceForm = document.getElementById("addServiceForm");
    const serviceList = document.getElementById("servicesList");
    let editingServiceId = null;

    function loadServices() {
        fetch("../ajax/fetch-services.php")
        .then(response => response.text())
        .then(data => {
            serviceList.innerHTML = data;
        });
    }

    serviceForm.addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(serviceForm);
        fetch("../ajax/add-service.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                serviceForm.reset();
                loadServices();
            } else {
                alert(data.message);
            }
        });
    });

    window.editService = function (id, name, description) {
        document.getElementById("service_id").value = id;
        document.getElementById("service_name").value = name;
        document.getElementById("description").value = description;
        document.querySelector(".submit-button").textContent = "Update Service";
    };

    window.deleteService = function (serviceId) {
        if (confirm("Are you sure you want to delete this service?")) {
            fetch("../ajax/delete-service.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `service_id=${serviceId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById(`service-${serviceId}`).remove();
                } else {
                    alert(data.message);
                }
            });
        }
    };

    loadServices();
});
