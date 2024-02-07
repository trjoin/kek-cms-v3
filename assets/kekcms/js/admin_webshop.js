document.addEventListener("DOMContentLoaded", function(){
    
    //webshop kategória törlés
    if(document.querySelectorAll(".shop_kategoria_torles")){
        document.querySelectorAll(".shop_kategoria_torles").forEach(btn => {
            btn.addEventListener("click", shop_kategoria_torles);
        });
    }
    
});

function shop_kategoria_torles(e){
    
    if(!confirm("Valóban törölni szeretné a főkategóriát?"))
        return false;
    
    let id = e.target.getAttribute("data-id");
    
    let data = new FormData();
    data.append('muvelet', "fokategoria_torles");
    data.append('id', id);

    fetch("/wp-admin/ajax/webshop.php", {
        method: 'POST',
        body: data
    }).then(
        response => response.json()
    )
    .then(json => {
        if (json.status == "success") {
            //sor törlése
            let row = e.target.closest(".row");
            row.classList.add("fadeout");
            setTimeout(function () {
                row.classList.add("d-none")
            }, 1000);
        }

        show_notification(json.status, json.msg, 5500);
    });
    
}