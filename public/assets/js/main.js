
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


// function ajoutPanier(event) {

//     event.preventDefault();
//     let url = document.getElementById("ajoutPanier").action;
//     let number = document.querySelector('.number-cart')

//     fetch(url).then(response => {
//         response.json()
//             .then(response => {
//                 number.textContent = response.count
//                 alert('ajouté')

//             })
//     })
// }



let ap = document.querySelectorAll('.ap');
let deletePanier = document.querySelectorAll('.deletePanier');

for (let i = 0; i < deletePanier.length; i++) {
    deletePanier[i].addEventListener('click', ondelete);
}

for (let i = 0; i < ap.length; i++) {
    ap[i].addEventListener('click', ajoutPanier);

}



