document.addEventListener('DOMContentLoaded', function() {
    const message = sessionStorage.getItem('flash_message');
    const type = sessionStorage.getItem('flash_type') || 'success';
    if (message) {
        showFlash(message, type);
        sessionStorage.removeItem('flash_message');
        sessionStorage.removeItem('flash_type');
    }
    
});

/** 
 * Function to show flash message
 */
export function showFlash(message, type = 'success', duration = 5000) {

    const flash = document.getElementById('flash-message');

    // Set message and style class
    flash.textContent = message;
    flash.className = `flash-message flash-${type}`;
    // flash.style.display = 'block';
    flash.style.opacity = '1';

    // Auto-hide after `duration` ms
    setTimeout(() => {
        flash.style.opacity = '0';
        setTimeout(() => {
            flash.style.display = 'none';
        }, 500); // fade out
    }, duration);

}