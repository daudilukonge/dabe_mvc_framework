function showLoader() {

    const overlay = document.getElementById('loader-overlay');
    overlay.classList.remove('hidden');
    document.getElementById('loader-message').textContent = 'Loading...';
    document.getElementById('loader-message').className = 'loader-title';
    document.querySelector('.dots').style.display = 'flex';
    document.getElementById('redirect-note').classList.add('hidden');
    
}

function showFlashMessage(type, message, redirect = false, url = '') {
    const loaderMsg = document.getElementById('loader-message');
    const dots = document.querySelector('.dots');
    const redirectNote = document.getElementById('redirect-note');

    // Hide dots, show message
    dots.style.display = 'none';
    loaderMsg.textContent = message;
    loaderMsg.className = 'loader-title ' + (type === 'success' ? 'success' : 'error');

    if (type === 'success' && redirect) {

        redirectNote.classList.remove('hidden');
        setTimeout(() => {
            window.location.href = url;
        }, 3000);

    } else {

        setTimeout(() => {
            document.getElementById('loader-overlay').classList.add('hidden');
        }, 4000);

    }

}
