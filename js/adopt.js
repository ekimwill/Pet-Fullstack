
function scrollToPets() {
    const petsSection = document.getElementById('petsSection');
    if (petsSection) {
      petsSection.scrollIntoView({ behavior: "smooth" });
    }
  }
  
  function openAdoptionModal() {
    document.getElementById('adoptionModal').style.display = 'flex';
  }
  function closeAdoptionModal() {
    document.getElementById('adoptionModal').style.display = 'none';
  }
  
  document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.adopt-button').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        if (!isLoggedIn) {
          alert('Please log in first to adopt a pet.');
        } else {
          document.getElementById('pet_id').value = this.getAttribute('data-pet-id');
          openAdoptionModal();
        }
      });
    });
  
    const closeButton = document.querySelector('.close-button');
    if (closeButton) {
      closeButton.addEventListener('click', closeAdoptionModal);
    }
  
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
  
    window.addEventListener('click', function(e) {
      const modal = document.getElementById('adoptionModal');
      if (e.target === modal) {
        closeAdoptionModal();
      }
    });
  });
  