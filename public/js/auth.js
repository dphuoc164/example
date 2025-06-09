$(document).ready(function () {
    // CSRF Token setup
    const token = $('meta[name="csrf-token"]').attr("content");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": token,
        },
    });

    // Form submission handler
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        const form = $(this);

        // Disable submit button to prevent double submission
        form.find('button[type="submit"]').prop("disabled", true);

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                form.find('button[type="submit"]').prop("disabled", false);

                if (xhr.status === 419) {
                    // Refresh page to get new CSRF token
                    alert("Phiên làm việc đã hết hạn. Trang sẽ tải lại.");
                    window.location.reload();
                } else if (xhr.status === 422) {
                    // Show validation errors
                    const errors = xhr.responseJSON.errors;
                    $(".error-message").remove(); // Clear previous errors

                    Object.keys(errors).forEach((field) => {
                        $(`#${field}`).after(
                            `<span class="error-message text-red-600 text-sm">${errors[field][0]}</span>`
                        );
                    });
                }
            },
        });
    });
});
