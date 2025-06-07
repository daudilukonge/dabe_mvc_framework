document.addEventListener('DOMContentLoaded', function () {

    let logoutButton = document.getElementById('logout-button');
    logoutButton.addEventListener('click', () => {

        fetch('/api/users/logout', {

            method: 'POST',
            headers: {
                "ACCESS-TOKEN": "Bearer " + localStorage.getItem('access_token'),
                "REFRESH-TOKEN": "Bearer " + localStorage.getItem('refresh_token'),
            }

        })
        .then(result => result.json())
        .then(data => {
            
            if (data.status === 1) {

                window.location.href = '/login';

            } else if (data.status === 0 && (data.error_status === 'Expired Token' || data.error_status === 'Redirect')) {

                window.location.href = '/login';
                
            }
            
        })
        .catch(error => {

            console.log("Error: ", error);
            
        })
        
    })
});