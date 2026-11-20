document.addEventListener('DOMContentLoaded', function () {
    var favorites = document.querySelectorAll('.favorite-btn');

    if (favorites.length > 0) {
        favorites.forEach(button => {
            button.addEventListener('click', function () {

                var url = this.dataset.url;

                fetch(url, {
                    method: 'POST',
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Error ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(favorite => {
                        if (favorite.success) {
                            if (favorite.added) {
                                this.textContent = '♥ Quitar de favoritos';
                            } else {
                                this.textContent = '♡ Añadir a favoritos';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error en la petición:', error.message);
                    });
            });
        });
    }
});
function searchMovies(text, inputSuggestions, url) {

    var suggestions = document.getElementById('suggestions');
    text = text.trim();

    if (text.length < 2) {
        suggestions.innerHTML = '';
        return;
    }

    fetch(`${url}?search=${encodeURIComponent(text)}`)
        .then(response => response.json())
        .then(movies => {
            suggestions.innerHTML = '';
            movies.forEach(movie => {
                var li = document.createElement('li');
                li.textContent = movie.title;

                li.addEventListener('click', function () {
                    inputSuggestions.value = movie.title;
                })
                suggestions.appendChild(li);
            });
        });
}
