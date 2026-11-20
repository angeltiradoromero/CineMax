document.addEventListener('DOMContentLoaded', function () {
    var movie = document.querySelectorAll('.movie');

    if (movie.length > 0) {
        movie.forEach(data => {
            data.addEventListener('click', function () {
                var id = data.dataset.id;
                console.log(id);
                window.location.href = '/pelicula/' + id;
            });
        });
    }

    var inputSuggestions = document.getElementById('search');

    if (inputSuggestions) {

        console.log(inputSuggestions.dataset.url);

        inputSuggestions.addEventListener('input', function () {
            searchMovies(this.value, inputSuggestions, inputSuggestions.dataset.url);
        });

        // Seleccionar sugerencias con las flechas del teclado.
        inputSuggestions.addEventListener('keydown', function (e) {

            var suggestions = document.getElementById('suggestions');
            var items = suggestions.querySelectorAll('li');

            if (items.length === 0) {
                return;
            }

            var active = suggestions.querySelector('.active');
            var index = Array.from(items).indexOf(active);

            if (e.key === 'ArrowDown') {
                e.preventDefault();

                if (index < items.length - 1) {
                    index++;
                } else {
                    index = 0;
                }
            }

            if (e.key === 'ArrowUp') {
                e.preventDefault();

                if (index > 0) {
                    index--;
                } else {
                    index = items.length - 1;
                }
            }

            if (e.key === 'Enter' && active) {
                e.preventDefault();
                inputSuggestions.value = active.textContent;
                suggestions.innerHTML = '';
                return;
            }

            items.forEach(item => {
                item.classList.remove(
                    'active',
                    'bg-red-600/20',
                    'text-red-500',
                    'border-l-4',
                    'border-red-600'
                );
            });

            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                items[index].classList.add(
                    'active',
                    'bg-red-600/20',
                    'text-red-500',
                    'border-l-4',
                    'border-red-600'
                );
            }
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
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }

            return response.json();
        })
        .then(movies => {
            suggestions.innerHTML = '';

            movies.forEach(movie => {

                var li = document.createElement('li');
                li.textContent = movie.title;

                li.classList.add(
                    'cursor-pointer',
                    'px-4',
                    'py-3',
                    'text-gray-300',
                    'transition',
                    'hover:bg-red-600/20',
                    'hover:text-red-500'
                );

                li.addEventListener('click', function () {
                    inputSuggestions.value = movie.title;
                    suggestions.innerHTML = '';
                    window.location.href = '/pelicula/' + movie.id;
                });

                suggestions.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error en la petición:', error.message);
        });
}
