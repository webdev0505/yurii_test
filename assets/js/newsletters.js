document.addEventListener("DOMContentLoaded", function () {
    var submitButton = document.querySelector("button[type='submit']");
    var emailInput = document.querySelector("input[type='email']");
    var subscribeAllCheckbox = document.querySelector("#subscribe-all");
    var subscribeButtons = document.querySelectorAll(".subscribe");
    // Toggle 'selected' class when clicking .subscribe
    subscribeButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            button.classList.toggle("selected");
            var img = button.querySelector("img");
            if (img) {
                var isSelected = button.classList.contains("selected");
                img.setAttribute("src", isSelected ? "images/icon_image2.svg" : "images/icon_image.svg");
                img.setAttribute("alt", isSelected ? "Subscribed" : "Subscribe");
            }
        });
    });
    // Handle Submit Button Click
    submitButton.addEventListener("click", function (event) {
        event.preventDefault();
        var email = emailInput.value.trim();
        if (!email || !email.includes("@")) {
            alert("Please enter a valid email address.");
            return;
        }
        var selectedIds = [];
        var subscribeElements = document.querySelectorAll(".subscribe");
        if (subscribeAllCheckbox.checked) {
            // If "Subscribe to all" is checked, grab all IDs
            subscribeElements.forEach(function (element) {
                var dataId = element.getAttribute("data-id");
                if (dataId)
                    selectedIds.push(dataId);
                // Ensure all are visually selected
                element.classList.add("selected");
                var img = element.querySelector("img");
                if (img) {
                    img.setAttribute("src", "images/icon_image2.svg");
                    img.setAttribute("alt", "Subscribed");
                }
            });
        }
        else {
            // Otherwise, grab only elements with 'selected' class
            subscribeElements.forEach(function (element) {
                if (element.classList.contains("selected")) {
                    var dataId = element.getAttribute("data-id");
                    if (dataId)
                        selectedIds.push(dataId);
                }
            });
        }
        if (selectedIds.length === 0) {
            alert("Please select at least one newsletter.");
            return;
        }
        // Prepare API payload
        var payload = {
            email: email,
            subscribe: subscribeAllCheckbox.checked,
            ids: selectedIds
        };
        // Send request to WordPress API
        fetch("/wp-json/custom/v1/subscribe", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
            alert("Subscription successful!");
            console.log("Response:", data);
        })
            .catch(function (error) {
            console.error("Error:", error);
            alert("Something went wrong.");
        });
    });
});
