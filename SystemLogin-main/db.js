document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('form');
    const passwordInput = document.querySelector('input[type="password"]');

    if (loginForm && passwordInput) {
        loginForm.addEventListener('submit', (e) => {
            const password = passwordInput.value;

            // Regex requirements
            const minLength = password.length >= 8;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

            if (!minLength || !hasUpper || !hasLower || !hasNumber || !hasSpecial) {
                e.preventDefault(); // Prevent form submission
                alert(
                    'Password must contain:\n' +
                    '- At least 8 characters\n' +
                    '- At least 1 uppercase letter\n' +
                    '- At least 1 lowercase letter\n' +
                    '- At least 1 number\n' +
                    '- At least 1 special character'
                );
            }
        });
    }
});