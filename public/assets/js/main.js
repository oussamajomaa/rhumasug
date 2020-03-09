
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


function ajouter(event) {

    event.preventDefault();
    const url = this.href;
    let number = document.querySelector('.number-cart')

    fetch(url).then(data => {
        data.json()
            .then(data => {
                number.textContent = data.count
            })
    })
}

let ap = document.querySelectorAll('.ap');
for (let i = 0; i < ap.length; i++) {
    ap[i].addEventListener('click', ajouter);
}

// function refresh(a, qte) {
//     let url = a.href + "?qte=" + qte;
//     fetch(url).then(response => {
//         response.json()
//             .then(response => {
//                 alert(response.message)
//             })
//     })
// }
// // // tous les boutons de ajouter au panier
// let refresh = document.querySelectorAll('.refreshPanier');
// refresh.forEach((a) => {
//     a.addEventListener("click", (e) => {
//         e.preventDefault();
//         // récupérer la quantité
//         let qte = a.parentNode.querySelector("input").value;
//         // récupérer le formulaire
//         let form = bouton.parentNode.parentNode;
//         refresh(form, qte);
//     });
// });


// function update(event) {

//     event.preventDefault();
//     let qte = this.parentNode.querySelector("input").value;
//     let montant=this.parentNode.parentNode.querySelector('span');
    
//     const url = this.href + "?qte=" + qte;

//     fetch(url).then(data => {
//         data.json()
//             .then(data => {
//                 montant.textContent = data.montant
//             })
//     })
// }

function update() {

    let qte = this.value;
    // let montant = this.parentNode.parentNode.querySelector('span');

    const url = this.querySelector('span').textContent + "?qte=" + qte;
    console.log(url)

    fetch(url).then(data => {
        data.json()
            .then(data => {

            })
    })
}

let qte = document.querySelectorAll('.inputQte');
for (let i = 0; i < qte.length; i++) {
    qte[i].addEventListener('change',function(){
    let url = this.parentNode.querySelector('span').textContent + "?qte=" + this.value
    let montant = this.parentNode.parentNode.querySelector('.montant')

    fetch(url).then(data => {
            data.json()
                .then(data => {
                    montant.textContent = data.montant
                })
        })
    });
}

// let refresh = document.querySelectorAll('.refreshPanier');
// let qte=document.querySelectorAll('.updateQte')
// for (let i = 0; i < refresh.length; i++) {
//     refresh[i].addEventListener('click', update);
// }


let theadTr=document.querySelectorAll('thead > tr');

for (let i=0;i<theadTr.length;i++){
    theadTr[i].addEventListener('click',()=>{
        let trtd=theadTr[i].parentNode.parentNode.querySelectorAll('.trtd')
        $(trtd).toggle('100')
        if (theadTr[i].querySelector('img').getAttribute('src') == 'assets/img/down1.png'){
            theadTr[i].querySelector('img').setAttribute('src','assets/img/up1.png')
        }
        else{
            theadTr[i].querySelector('img').setAttribute('src', 'assets/img/down1.png')
        }
        

    })
}


