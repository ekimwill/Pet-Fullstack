// We'll retrieve the variables isLoggedIn and currentUser from the inline <script> in adopt_user.php

// 1) Scroll to Pets
function scrollToPets() {
    const petsSection = document.getElementById('petsSection');
    if (petsSection) {
      petsSection.scrollIntoView({ behavior: "smooth" });
    }
  }
  
  // 2) Adoption Modal Functions
  function openAdoptionModal() {
    document.getElementById('adoptionModal').style.display = 'flex';
  }
  function closeAdoptionModal() {
    document.getElementById('adoptionModal').style.display = 'none';
  }
  
  // 3) Attach event listeners to Adopt buttons
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.adopt-button').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        if (!isLoggedIn) {
          alert('Please log in first to adopt a pet.');
        } else {
          // Set the hidden pet_id field based on the clicked button
          document.getElementById('pet_id').value = this.getAttribute('data-pet-id');
          openAdoptionModal();
        }
      });
    });
  
    // Close modal when the close button is clicked
    const closeButton = document.querySelector('.close-button');
    if (closeButton) {
      closeButton.addEventListener('click', closeAdoptionModal);
    }
  
    // 4) Adoption Form Submission (AJAX)
    const adoptionForm = document.getElementById('adoptionForm');
    if (adoptionForm) {
      adoptionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const petId = document.getElementById('pet_id').value;
        const adopterAge = document.getElementById('adopterAge').value;
        const adoptionReason = document.getElementById('adoptionReason').value;
        
        if (!petId || !adopterAge || !adoptionReason) {
          alert("Please complete all fields.");
          return;
        }
        
        const formData = new FormData();
        formData.append('pet_id', petId);
        formData.append('adopterAge', adopterAge);
        formData.append('adoptionReason', adoptionReason);
        // Pass the current user if needed
        formData.append('requester', currentUser);
  
        fetch('submit_adoption.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
          closeAdoptionModal();
        })
        .catch(error => {
          console.error('Error:', error);
          alert('There was an error submitting your adoption request.');
        });
      });
    }
  
    // 5) Close the modal if user clicks outside the modal content
    window.addEventListener('click', function(e) {
      const modal = document.getElementById('adoptionModal');
      if (e.target === modal) {
        closeAdoptionModal();
      }
    });
  });
  