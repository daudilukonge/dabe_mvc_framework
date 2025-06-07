setTimeout(() => {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(msg => {
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 500); // remove from DOM after fade out
    }); 
}, 5000);