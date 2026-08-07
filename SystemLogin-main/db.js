// Include SweetAlert2 dynamically if not already added in HTML
if (!document.getElementById('sweetalert-script')) {
    const script = document.createElement('script');
    script.id = 'sweetalert-script';
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(script);
}

document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.querySelector("form");
    const passwordInput = document.getElementById("password");

    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            const password = passwordInput.value;

            // Password Requirements regex checks
            const minLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (!minLength || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
                e.preventDefault(); // Stop form submission

                // Styled popup instead of standard black browser alert
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Password Format',
                    html: `
                        <div style="text-align: left; font-size: 14px; line-height: 1.6;">
                            <strong>Password must contain:</strong><br>
                            • At least 8 characters<br>
                            • At least 1 uppercase letter<br>
                            • At least 1 lowercase letter<br>
                            • At least 1 number<br>
                            • At least 1 special character
                        </div>
                    `,
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
});