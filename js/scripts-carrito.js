if (localStorage.getItem('isAuthenticated') !== 'true') {
    window.location.href = 'index.html';
    }
    
    function logout() {
        localStorage.removeItem('isAuthenticated');
    }