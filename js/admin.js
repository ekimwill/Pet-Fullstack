// Example: If you want to add any admin-specific JS functionality,
// you can start by ensuring the DOM is fully loaded.
document.addEventListener('DOMContentLoaded', function() {
    // Example: Confirmation message for deleting a product/pet is already handled via inline "confirm()" calls.
    
    // If you need to add any dynamic behavior specific to the admin dashboard,
    // such as an interactive approval/rejection system, you can add that here.
  
    // For instance, you might want to use AJAX for handling approvals/rejections:
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function(e) {
        // You could optionally intercept the submission here
        // and use AJAX instead of a full page reload.
        // For now, this is left as-is.
      });
    });
  
    // More admin-specific JS functions can be added here.
  });
  