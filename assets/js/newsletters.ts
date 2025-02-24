document.addEventListener("DOMContentLoaded", () => {
    const submitButton = document.querySelector("button[type='submit']") as HTMLButtonElement;
    const emailInput = document.querySelector("input[type='email']") as HTMLInputElement;
    const subscribeAllCheckbox = document.querySelector("#subscribe-all") as HTMLInputElement;
    const subscribeButtons = document.querySelectorAll(".subscribe");

    // Toggle 'selected' class when clicking .subscribe
    subscribeButtons.forEach(button => {
        button.addEventListener("click", () => {
            button.classList.toggle("selected");

            const img = button.querySelector("img");
            if (img) {
                const isSelected = button.classList.contains("selected");
                img.setAttribute("src", isSelected ? "images/icon_image2.svg" : "images/icon_image.svg");
                img.setAttribute("alt", isSelected ? "Subscribed" : "Subscribe");
            }
        });
    });

    // Handle Submit Button Click
    submitButton.addEventListener("click", (event) => {
        event.preventDefault();

        const email = emailInput.value.trim();
        if (!email || !email.includes("@")) {
            alert("Please enter a valid email address.");
            return;
        }

        let selectedIds: string[] = [];
        const subscribeElements = document.querySelectorAll(".subscribe");

        if (subscribeAllCheckbox.checked) {
            // If "Subscribe to all" is checked, grab all IDs
            subscribeElements.forEach((element) => {
                const dataId = element.getAttribute("data-id");
                if (dataId) selectedIds.push(dataId);

                // Ensure all are visually selected
                element.classList.add("selected");
                const img = element.querySelector("img");
                if (img) {
                    img.setAttribute("src", "images/icon_image2.svg");
                    img.setAttribute("alt", "Subscribed");
                }
            });
        } else {
            // Otherwise, grab only elements with 'selected' class
            subscribeElements.forEach((element) => {
                if (element.classList.contains("selected")) {
                    const dataId = element.getAttribute("data-id");
                    if (dataId) selectedIds.push(dataId);
                }
            });
        }

        if (selectedIds.length === 0) {
            alert("Please select at least one newsletter.");
            return;
        }

        // Prepare API payload
        const payload = {
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
        .then(response => response.json())
        .then(data => {
            alert("Subscription successful!");
            console.log("Response:", data);
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Something went wrong.");
        });
    });
});
