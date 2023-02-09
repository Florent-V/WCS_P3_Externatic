import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect()
    {
        //let bookmark = document.getElementById('fav-company');
        let bookmarks = document.getElementsByClassName('fav-company');

        //bookmark.addEventListener('click', addToFavorite);
        for (let book of bookmarks) {
            book.addEventListener('click', addToFavorite);
        }

        function addToFavorite(event)
        {
            event.preventDefault();
            // Get the link object you click in the DOM
            let favoriteCompanyLink = event.currentTarget;
            let link = favoriteCompanyLink.href;
            // Send an HTTP request with fetch to the URI defined in the href
            fetch(link)
                // Extract the JSON from the response
                .then(res => res.json())
                // Then update the icon
                .then(function (res) {
                    let linktext = favoriteCompanyLink.firstElementChild;
                    let bookmarkIcon = favoriteCompanyLink.lastElementChild;
                    if (res.isInFavorite) {
                        linktext.innerHTML = 'Retirer l\'entreprise des favoris';
                        bookmarkIcon.classList.remove('bi-heart'); // Remove the .bi-heart (empty heart) from classes in <i> element
                        bookmarkIcon.classList.add('bi-heart-fill'); // Add the .bi-heart-fill (full heart) from classes in <i> element
                    } else {
                        linktext.innerHTML = 'Mettre l\'entreprise en favori';
                        bookmarkIcon.classList.remove('bi-heart-fill'); // Remove the .bi-heart-fill (full heart) from classes in <i> element
                        bookmarkIcon.classList.add('bi-heart'); // Add the .bi-heart (empty heart) from classes in <i> element
                    }
                });
        }
    }
}
