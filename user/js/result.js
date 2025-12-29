   // Subject data based on the image provided
   const subjects = [
    {
        name: "Bahasa Melayu",
        grade: "A",
        comment: "Pelajar menunjukkan penguasaan yang sangat baik dalam subjek. Kefahaman menyeluruh dan boleh menyelesaikan soalan dengan tepat serta yakin.",
        colorClass: "grade-a"
    },
    {
        name: "Matematik",
        grade: "A",
        comment: "Pelajar menunjukkan penguasaan yang sangat baik dalam subjek. Kefahaman menyeluruh dan boleh menyelesaikan soalan dengan tepat serta yakin.",
        colorClass: "grade-a"
    },
    {
        name: "Pendidikan Agama Islam",
        grade: "A",
        comment: "Pelajar menunjukkan penguasaan yang sangat baik dalam subjek. Kefahaman menyeluruh dan boleh menyelesaikan soalan dengan tepat serta yakin.",
        colorClass: "grade-a"
    },
    {
        name: "Bahasa Inggeris",
        grade: "B",
        comment: "Pelajar memahami kehendak konsep dan menunjukkan prestasi yang baik. Ada ruang untuk penambahbaikan dalam beberapa aspek.",
        colorClass: "grade-b"
    },
    {
        name: "Sains",
        grade: "B",
        comment: "Pelajar memahami kehendak konsep dan menunjukkan prestasi yang baik. Ada ruang untuk penambahbaikan dalam beberapa aspek.",
        colorClass: "grade-b"
    },
    {
        name: "Bahasa Arab",
        grade: "C",
        comment: "Pelajar menunjukkan penguasaan asas dalam subjek tetapi masih memerlukan bimbingan untuk memahami topik-topik tertentu dengan lebih mendalam.",
        colorClass: "grade-c"
    }
];

// Function to create subject cards
function createSubjectCards() {
    const container = document.getElementById('subjectCards');
    
    subjects.forEach((subject, index) => {
        // Create card element
        const card = document.createElement('div');
        card.className = 'subject-card';
        card.style.animationDelay = `${index * 0.1 + 0.6}s`;
        
        // Card content
        card.innerHTML = `
            <div class="subject-header">
                <div class="subject-name">${subject.name}</div>
                <div class="subject-grade ${subject.colorClass}">${subject.grade}</div>
            </div>
            <div class="subject-comment">${subject.comment}</div>
        `;
        
        container.appendChild(card);
    });
}

// Function to handle download button click
function setupDownloadButton() {
    const downloadBtn = document.getElementById('downloadBtn');
    
    downloadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show loading animation
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        
        // Simulate download process
        setTimeout(() => {
            downloadBtn.innerHTML = '<i class="fas fa-check"></i> Berjaya Dimuat Turun!';
            
            // Reset button after 2 seconds
            setTimeout(() => {
                downloadBtn.innerHTML = originalText;
            }, 2000);
            
            // In real application, trigger PDF download here
            alert('Slip keputusan telah berjaya dimuat turun!');
        }, 1500);
    });
}

// Function to handle print button
function setupPrintButton() {
    const printBtn = document.querySelector('.fa-print').closest('.share-button');
    
    printBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
}

// Function to handle share buttons
function setupShareButtons() {
    const shareButtons = document.querySelectorAll('.share-button');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('fa-whatsapp')) {
                alert('Berkongsi melalui WhatsApp...');
                // In real app: window.open(`https://wa.me/?text=${encodeURIComponent(document.title + ' ' + window.location.href)}`);
            } else if (icon.classList.contains('fa-envelope')) {
                alert('Berkongsi melalui Email...');
                // In real app: window.location.href = `mailto:?subject=${encodeURIComponent(document.title)}&body=${encodeURIComponent(window.location.href)}`;
            }
        });
    });
}

// Function to set up back button
function setupBackButton() {
    const backBtn = document.querySelector('.back-button');
    
    backBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Add animation before redirecting
        document.querySelector('.result-container').style.animation = 'slideUp 0.8s ease-out reverse forwards';
        
        setTimeout(() => {
            alert('Anda akan dikembalikan ke halaman semakan.');
            // In real app: window.location.href = 'check-result.html';
        }, 500);
    });
}

// Function to animate grade display
function animateGrades() {
    const gradeElements = document.querySelectorAll('.subject-grade');
    
    gradeElements.forEach((element, index) => {
        // Reset to small size
        element.style.transform = 'scale(0.5)';
        element.style.opacity = '0';
        
        // Animate to full size with delay
        setTimeout(() => {
            element.style.transition = 'transform 0.5s ease-out, opacity 0.5s ease-out';
            element.style.transform = 'scale(1)';
            element.style.opacity = '1';
            
            // Add subtle continuous animation
            setTimeout(() => {
                element.style.animation = 'pulseBtn 2s infinite';
            }, 500);
        }, 600 + (index * 100));
    });
}

// Function to add hover effects to cards
function setupCardHoverEffects() {
    const cards = document.querySelectorAll('.subject-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const gradeElement = this.querySelector('.subject-grade');
            gradeElement.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            const gradeElement = this.querySelector('.subject-grade');
            gradeElement.style.transform = 'scale(1) rotate(0)';
        });
    });
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    createSubjectCards();
    setupDownloadButton();
    setupPrintButton();
    setupShareButtons();
    setupBackButton();
    
    // Animate grades after a short delay
    setTimeout(() => {
        animateGrades();
        setupCardHoverEffects();
    }, 800);
    
    // Update summary values with animation
    const summaryValues = document.querySelectorAll('.summary-value');
    summaryValues.forEach((value, index) => {
        value.style.opacity = '0';
        value.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            value.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            value.style.opacity = '1';
            value.style.transform = 'translateY(0)';
        }, 1000 + (index * 200));
    });
});