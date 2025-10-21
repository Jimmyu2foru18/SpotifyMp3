/**
 * Form validation with real-time feedback
 */

document.addEventListener('DOMContentLoaded', function() {
    // Login form validation
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        const emailInput = loginForm.querySelector('input[name="email"]');
        const passwordInput = loginForm.querySelector('input[name="password"]');
        
        // Email validation
        emailInput.addEventListener('input', function() {
            validateEmail(this);
        });
        
        // Password validation
        passwordInput.addEventListener('input', function() {
            validatePassword(this, 6);
        });
        
        // Form submission
        loginForm.addEventListener('submit', function(e) {
            if (!validateEmail(emailInput) || !validatePassword(passwordInput, 6)) {
                e.preventDefault();
            }
        });
    }
    
    // Registration form validation
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        const usernameInput = registerForm.querySelector('input[name="username"]');
        const emailInput = registerForm.querySelector('input[name="email"]');
        const passwordInput = registerForm.querySelector('input[name="password"]');
        const confirmPasswordInput = registerForm.querySelector('input[name="confirm_password"]');
        
        // Username validation
        usernameInput.addEventListener('input', function() {
            validateUsername(this);
        });
        
        // Email validation
        emailInput.addEventListener('input', function() {
            validateEmail(this);
        });
        
        // Password validation
        passwordInput.addEventListener('input', function() {
            validatePassword(this, 6);
            if (confirmPasswordInput.value) {
                validatePasswordMatch(confirmPasswordInput, this);
            }
        });
        
        // Confirm password validation
        confirmPasswordInput.addEventListener('input', function() {
            validatePasswordMatch(this, passwordInput);
        });
        
        // Form submission
        registerForm.addEventListener('submit', function(e) {
            if (!validateUsername(usernameInput) || 
                !validateEmail(emailInput) || 
                !validatePassword(passwordInput, 6) || 
                !validatePasswordMatch(confirmPasswordInput, passwordInput)) {
                e.preventDefault();
            }
        });
    }
    
    // Playlist form validation
    const playlistForm = document.getElementById('new-playlist-form');
    if (playlistForm) {
        const nameInput = playlistForm.querySelector('input[name="name"]');
        
        // Name validation
        nameInput.addEventListener('input', function() {
            validateRequired(this, 'Playlist name is required');
        });
        
        // Form submission
        playlistForm.addEventListener('submit', function(e) {
            if (!validateRequired(nameInput, 'Playlist name is required')) {
                e.preventDefault();
            }
        });
    }
    
    // Review form validation
    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        const textInput = reviewForm.querySelector('textarea[name="text"]');
        
        // Text validation
        textInput.addEventListener('input', function() {
            validateRequired(this, 'Review text is required');
        });
        
        // Form submission
        reviewForm.addEventListener('submit', function(e) {
            if (!validateRequired(textInput, 'Review text is required')) {
                e.preventDefault();
            }
        });
    }
});

/**
 * Validate email format
 */
function validateEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const value = input.value.trim();
    
    removeValidationMessage(input);
    
    if (value === '') {
        showValidationMessage(input, 'Email is required');
        return false;
    } else if (!emailRegex.test(value)) {
        showValidationMessage(input, 'Please enter a valid email address');
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Validate username
 */
function validateUsername(input) {
    const value = input.value.trim();
    
    removeValidationMessage(input);
    
    if (value === '') {
        showValidationMessage(input, 'Username is required');
        return false;
    } else if (value.length < 3) {
        showValidationMessage(input, 'Username must be at least 3 characters');
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Validate password
 */
function validatePassword(input, minLength) {
    const value = input.value;
    
    removeValidationMessage(input);
    
    if (value === '') {
        showValidationMessage(input, 'Password is required');
        return false;
    } else if (value.length < minLength) {
        showValidationMessage(input, `Password must be at least ${minLength} characters`);
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Validate password match
 */
function validatePasswordMatch(confirmInput, passwordInput) {
    const confirmValue = confirmInput.value;
    const passwordValue = passwordInput.value;
    
    removeValidationMessage(confirmInput);
    
    if (confirmValue === '') {
        showValidationMessage(confirmInput, 'Please confirm your password');
        return false;
    } else if (confirmValue !== passwordValue) {
        showValidationMessage(confirmInput, 'Passwords do not match');
        return false;
    }
    
    showValidationSuccess(confirmInput);
    return true;
}

/**
 * Validate required field
 */
function validateRequired(input, message) {
    const value = input.value.trim();
    
    removeValidationMessage(input);
    
    if (value === '') {
        showValidationMessage(input, message);
        return false;
    }
    
    showValidationSuccess(input);
    return true;
}

/**
 * Show validation error message
 */
function showValidationMessage(input, message) {
    // Remove any existing validation message
    removeValidationMessage(input);
    
    // Add error class to input
    input.classList.add('is-invalid');
    
    // Create validation message element
    const validationMessage = document.createElement('div');
    validationMessage.className = 'validation-message error';
    validationMessage.textContent = message;
    
    // Insert validation message after input
    input.parentNode.insertBefore(validationMessage, input.nextSibling);
}

/**
 * Show validation success
 */
function showValidationSuccess(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
}

/**
 * Remove validation message
 */
function removeValidationMessage(input) {
    // Remove validation classes
    input.classList.remove('is-invalid', 'is-valid');
    
    // Remove any existing validation message
    const validationMessage = input.parentNode.querySelector('.validation-message');
    if (validationMessage) {
        validationMessage.remove();
    }
}