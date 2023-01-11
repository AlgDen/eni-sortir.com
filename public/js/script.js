window.onload = function() {

    // let select = document.getElementById("creer_sortie_lieu");
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
$("#creer_sortie_lieu").change(function() {

    // let select = document.getElementById("creer_sortie_lieu");
    let idLieu = $("#creer_sortie_lieu").find(":selected").attr("data-idlieu");
    console.log(idLieu);

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

            console.log(data.nom)
        }
    });
});