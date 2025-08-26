$(document).ready(function () {
    // Install Wizard button click animation
    $(".install-wizard-btn").click(function (e) {
        e.preventDefault();

        // Add click animation
        $(this).addClass("clicked");

        // Show loading effect
        const originalText = $(this).find("span").text();
        const originalIcon = $(this).find("i").attr("class");

        $(this).find("i").attr("class", "bi bi-arrow-clockwise");
        $(this).find("span").text("Loading...");

        // Add spinning animation to icon
        $(this).find("i").css("animation", "spin 1s linear infinite");

        // Simulate loading and redirect
        setTimeout(() => {
            $(this).find("i").attr("class", "bi bi-gear-fill");
            $(this).find("span").text("Install Wizard");
            window.location.href = $(this).attr("href");
        }, 1500);
    });

    // Handle form submission
    $("#contactForm").submit(function (e) {
        e.preventDefault();
        $("#successMessage").removeClass("d-none");

        // Hide success message after 3 seconds
        setTimeout(function () {
            $("#successMessage").addClass("d-none");
        }, 3000);
    });

    // Original button click handler (for Send Message button)
    $("#contactForm .btn-primary").click(function (e) {
        // This will be handled by the form submit event above
    });
});

// Add spinning animation for loading icon
const style = document.createElement("style");
style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .install-wizard-btn.clicked {
                transform: scale(0.95);
                transition: transform 0.1s ease;
            }
        `;
document.head.appendChild(style);
