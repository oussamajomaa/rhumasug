
function ondelete(event) {

    event.preventDefault();
    const url = this.href;
    let number = document.querySelector('.number-cart')

    fetch(url).then(data => {
        data.json()
            .then(data => {
                number.textContent = data.count
                if (data.count == 0) {
                    location.href = '/';
                }
                $(this).closest('tr').remove();
            })
    })
}

let deletePanier = document.querySelectorAll('.deletePanier');

for (let i = 0; i < deletePanier.length; i++) {
    deletePanier[i].addEventListener('click', ondelete);
}


function ajoutPanier(form, qte) {
    let url = form.action + "?qte=" + qte;
    let number = document.querySelector('.number-cart');
    fetch(url).then(response => {
        response.json()
            .then(response => {
                number.textContent = response.count
            })
    })
}
// tous les boutons de ajouter au panier
let ap = document.querySelectorAll('.ap');

ap.forEach((bouton) => {
    bouton.addEventListener("click", (e) => {
        e.preventDefault();
        // récupérer la quantité
        let qte = bouton.parentNode.querySelector("input").value;
        // récupérer le formulaire
        let form = bouton.parentNode.parentNode;
        ajoutPanier(form, qte);
    });
});






