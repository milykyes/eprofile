document.addEventListener('DOMContentLoaded', function() {
    // Get all necessary elements
    const searchInput = document.querySelector('input[placeholder="Search students..."]');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const profileCards = document.querySelectorAll('.profile-card');

    // Function to filter students
    function filterStudents() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const activeFilter = document.querySelector('.filter-btn.active').textContent;

        profileCards.forEach(card => {
            const studentName = card.querySelector('.profile-name').textContent.toLowerCase();
            const studentFaculty = card.querySelector('.profile-faculty').textContent;
            const studentId = card.querySelector('.profile-detail:first-of-type').textContent;

            // Check search term
            const matchesSearch = 
                searchTerm === '' || 
                studentName.includes(searchTerm) || 
                studentId.includes(searchTerm);

            // Check faculty filter
            const matchesFaculty = 
                activeFilter === 'All' || 
                studentFaculty === activeFilter;

            // Show or hide card based on filters
            card.closest('.col-lg-4').style.display = 
                matchesSearch && matchesFaculty ? 'block' : 'none';
        });
    }

    // Add event listener to search input
    searchInput.addEventListener('input', filterStudents);

    // Add event listeners to faculty filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Apply filters
            filterStudents();
        });
    });
});