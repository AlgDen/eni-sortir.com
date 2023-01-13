window.onload = function() {
    let idLieu = $("#creer_sortie_lieu").find(":selected").attr("data-idlieu");
    $.ajax({
        url : "lieuData",
        type : 'GET',
        dataType: "json",
        data: {
            "idLieu": idLieu
        },
        success: function (data)
        {
            $("#creer_sortie_ville").val(data.ville);
            $("#creer_sortie_cp").val(data.cp);
            $("#creer_sortie_rue").val(data.rue);
            $("#creer_sortie_latitude").val(data.latitude);
            $("#creer_sortie_longitude").val(data.longitude);
        }
    })};
//Listener sur le changement de lieu dans le select
$("#creer_sortie_lieu").change(function() {

    // on récupère l'id du lieu actuellement selectionné
    let idLieu = $("#creer_sortie_lieu").find(":selected").attr("data-idlieu");
    // Requete ajax pour envoyer l'id du lieu au controller
    $.ajax({
        url : "lieuData",
        type : 'GET',
        dataType: "json",
        data: {
            "idLieu": idLieu
        },
        success: function (data)
        {
            //on récupères les données envoyées par le controller et on remplit les champs
            $("#creer_sortie_ville").val(data.ville);
            $("#creer_sortie_cp").val(data.cp);
            $("#creer_sortie_rue").val(data.rue);
            $("#creer_sortie_latitude").val(data.latitude);
            $("#creer_sortie_longitude").val(data.longitude);
        }
    });
});