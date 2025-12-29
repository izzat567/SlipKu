//   // Toggle password visibility
//   document.getElementById('passwordToggle').addEventListener('click', function() {
//     const passwordInput = document.getElementById('adminPassword');
//     const icon = this.querySelector('i');
    
//     if (passwordInput.type === 'password') {
//         passwordInput.type = 'text';
//         icon.classList.remove('fa-eye');
//         icon.classList.add('fa-eye-slash');
//     } else {
//         passwordInput.type = 'password';
//         icon.classList.remove('fa-eye-slash');
//         icon.classList.add('fa-eye');
//     }
// });

// // Form submission handler
// document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     // Get form values
//     const adminName = document.getElementById('adminName').value.trim();
//     const adminIC = document.getElementById('adminIC').value.trim();
//     const adminPassword = document.getElementById('adminPassword').value;
    
//     // Simple validation (for demonstration)
//     const errorMessage = document.getElementById('errorMessage');
//     const errorText = document.getElementById('errorText');
    
//     // Reset previous error
//     errorMessage.style.display = 'none';
    
//     // Check if all fields are filled
//     if (!adminName || !adminIC || !adminPassword) {
//         errorText.textContent = 'Sila isi semua medan yang diperlukan.';
//         errorMessage.style.display = 'flex';
//         return;
//     }
    
//     // Validate IC number (basic validation)
//     if (!/^\d{12}$/.test(adminIC)) {
//         errorText.textContent = 'Nombor kad pengenalan mesti mengandungi 12 digit nombor.';
//         errorMessage.style.display = 'flex';
//         return;
//     }
    
//     // Show loading animation
//     const loadingOverlay = document.getElementById('loadingOverlay');
//     loadingOverlay.style.display = 'flex';
    
//     // Simulate API call/authentication
//     setTimeout(() => {
//         loadingOverlay.style.display = 'none';
        
//         // For demo purposes, accept specific credentials
//         const demoCredentials = {
//             name: 'admin',
//             ic: '800101123456',
//             password: 'admin123'
//         };
        
//         // Check credentials (in real app, this would be server-side)
//         if (adminName === demoCredentials.name && 
//             adminIC === demoCredentials.ic && 
//             adminPassword === demoCredentials.password) {
            
//             // Show success message
//             const successMessage = document.getElementById('successMessage');
//             successMessage.style.display = 'block';
            
//             // Simulate redirect to admin dashboard
//             setTimeout(() => {
//                 alert('Log masuk berjaya! Dalam aplikasi sebenar, anda akan dialihkan ke dashboard admin.');
//                 // In real app: window.location.href = 'admin-dashboard.php';
//             }, 1500);
            
//         } else {
//             // Show error for incorrect credentials
//             errorText.textContent = 'Nama, kad pengenalan atau kata laluan tidak tepat.';
//             errorMessage.style.display = 'flex';
//             errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
//         }
//     }, 2000);
// });

// // Forgot password link
// document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
//     e.preventDefault();
//     alert('Sila hubungi pentadbir sistem untuk menetapkan semula kata laluan anda.');
// });

// // Back button
// document.querySelector('.back-button').addEventListener('click', function(e) {
//     e.preventDefault();
//     // In real app, redirect to homepage
//     alert('Anda akan dikembalikan ke laman utama.');
//     // window.location.href = 'index.php';
// });

// // Input field animations
// const inputs = document.querySelectorAll('.form-input');
// inputs.forEach(input => {
//     input.addEventListener('focus', function() {
//         this.parentElement.querySelector('.focus-border').style.width = '100%';
//     });
    
//     input.addEventListener('blur', function() {
//         if (!this.value) {
//             this.parentElement.querySelector('.focus-border').style.width = '0';
//         }
//     });
// });

// // Auto-hide error message when user starts typing
// inputs.forEach(input => {
//     input.addEventListener('input', function() {
//         document.getElementById('errorMessage').style.display = 'none';
//     });
// });