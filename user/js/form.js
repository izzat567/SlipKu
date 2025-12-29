 // Simple JavaScript for form submission animation (optional)
 document.getElementById('studentResultForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading animation
    const loadingOverlay = document.getElementById('loadingOverlay');
    const successMessage = document.getElementById('successMessage');
    
    loadingOverlay.style.display = 'flex';
    
    // Simulate processing delay
    setTimeout(() => {
        loadingOverlay.style.display = 'none';
        successMessage.style.display = 'block';
        
        // Hide form and scroll to success message
        successMessage.scrollIntoView({ behavior: 'smooth' });
        
        // Reset form after 3 seconds
        setTimeout(() => {
            this.reset();
            successMessage.style.display = 'none';
        }, 3000);
    }, 2000);
});