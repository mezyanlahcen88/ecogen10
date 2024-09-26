var reglements = [];
var newReglement = {
    id: "",
    document_id: "",
    document_type: "commande",
    ref_reg: "",
    num_order: "",
    nature_reg: "",
    parent_type: "",
    parent_id: "",
    mode_reg: "",
    comment: "",
    amount_reg: "",
    date_reg: "",
};

function tableReglements() {
    var data = JSON.parse(localStorage.getItem("product_commandEdit")) || {};
    var listeReg = data.detailsReglement || [];
    // console.log(listeReg);

    // Vide le corps de la table
    $("#RegTableBody").empty();

    // Parcourt la liste des reglements et ajoute chaque ligne à la table
    listeReg.forEach((reg, index) => {
        appendTableRegRow(reg, index);
    });

    // calculeTotals();
}

function appendTableRegRow(reg, index) {
    var row =
        '<tr class="text-center">' +
        "<td>" +
        reg.mode_reg +
        "</td>" +
        "<td>" +
        reg.amount_reg +
        "</td>" +
        "<td>" +
        reg.date_reg +
        "</td>" +
        '<td>' +
        '    <button type="button" class="btn editerReglement" data-index="' + index + '"><i class="las la-edit text-danger fs-1"></i></button> <button type="button" class="btn deleteReglement" data-index="' + index + '"><i class="las la-times text-danger fs-1"></i></button>' +
        '</td>' +
        "</tr>";

    $("#RegTableBody").append(row);
}

$("#RegTableBody").on('click', '.deleteReglement', function () {
    let index = $(this).data('index');
    $("#storeReglement").removeClass("storeReglement").addClass("updateReglement");
    let data = JSON.parse(localStorage.getItem('product_commandEdit'));
    if (!data || !data.detailsReglement) {
        console.log('Erreur : Aucune donnée de reglement trouvée dans le localStorage.');
        return;
    }
    let listeReglements = data.detailsReglement;
    let montantSaisie = listeReglements[index].amount_reg;
    let rest_payer = parseFloat($("#total_restanter").text());
    let total_ttc = parseFloat($("#total_ttc").text());

    if ((rest_payer + montantSaisie) <= total_ttc) {
        rest_payer += montantSaisie;
        $("#total_restanter").text(rest_payer.toFixed(2));

        listeReglements.splice(index, 1);
        localStorage.setItem('product_commandEdit', JSON.stringify(data));

        $("#montantPayer").val(0);
        $('select[name="reglement"]').val("").trigger('change.select2'); // Déselectionner l'option        $("#check_ref").val("");
        $("#commentReg").val("");
        $("#check_ref").val("");
        console.log('Reglement supprimé avec succès.');

        if (total_ttc >= rest_payer) {
            $('#montantPayer').val(0);
            $('#montantPayer').prop('readonly', false);
        }
    } else {
        Swal.fire("Super!", "Le montant saisi n'est pas valide ou plus grand que le reste à payer", "error");
    }
    console.log("montantSaisie : " + montantSaisie);
    console.log("total_restanter : " + rest_payer);

    tableReglements();
});

$("#RegTableBody").on('click', '.editerReglement', function () {
    let index = $(this).data('index');
    console.log("index");
    console.log(index);
    $("#storeReglement").hide();
    $("#updateReglement").show();
    // Trouver le bouton "delete" dans le même parent
    var deleteBtn = $(this).closest("tr").find(".deleteReglement");
    // Cacher le bouton "delete"
    deleteBtn.hide();
    let data = JSON.parse(localStorage.getItem('product_commandEdit'));
    if (!data || !data.detailsReglement) {
        console.log('Erreur : Aucune donnée de reglement trouvée dans le localStorage.');
        return;
    }
    let listeReglements = data.detailsReglement;
    let montantSaisie = listeReglements[index].amount_reg;
    let modePaiment = listeReglements[index].mode_reg;
    let num_order = listeReglements[index].num_order;
    let comment = listeReglements[index].comment;
    let rest_payer = parseFloat($("#total_restanter").text());
    let total_ttc = parseFloat($("#total_ttc").text());
    localStorage.setItem('montantEdit', montantSaisie);
    if ((rest_payer + montantSaisie) <= total_ttc) {
        rest_payer += montantSaisie;
        $("#total_restanter").text(rest_payer.toFixed(2));
    }

    if (modePaiment ==='Cheque') {
         $("#check_ref, label[for='check_ref']").show();
    } else {
        $("#check_ref, label[for='check_ref']").hide();
        num_order = "";
    }
    $("#montantPayer").val(montantSaisie);
    $('select[name="reglement"]').val(modePaiment).trigger('change.select2');
    $("#check_ref").val(num_order);
    $("#commentReg").val(comment);
    $("#index").val(index);
    $('#montantPayer').prop('readonly', false);
    $("#btnValider").attr("id", "btnModifier");
});

// Lorsque la valeur du select change
$('select[name="reglement"]').on('change', function () {
    var modePaiement = $(this).val();

    // Si le mode de paiement est "chèque"
    if (modePaiement === "Cheque") {
        // Afficher l'input check_ref
        // $("#check_ref").show();
         $("#check_ref, label[for='check_ref']").show();
    } else {
        // Sinon, cacher l'input check_ref
         $("#check_ref, label[for='check_ref']").hide();
    }

    // Déclencher l'événement 'change.select2'
    $('select[name="reglement"]').trigger('change.select2');
});

// Déclencher l'événement 'change' une fois au chargement pour initialiser
$('select[name="reglement"]').trigger('change');




$("#btnValider").on("click", function (e) {
    e.preventDefault();
    if ($(this).attr("id") === "btnValider") {
        var montantTotalPayer = 0;
        // var data = JSON.parse(localStorage.getItem("reglements")) || [];
        var commande = JSON.parse(localStorage.getItem("product_commandEdit"));
        var data = commande.detailsReglement;
        if (data && data.length > 0) {
            // Calcule le prix hors taxe
            montantTotalPayer = data.reduce(
                (acc, reg) => acc + reg.amount_reg,
                0
            );
        }
        var reg = Object.assign({}, newReglement);
        var document_id = commande.object.id;
        var parent_id = commande.object.client_id;
        console.log(montantTotalPayer);
        var total_ttc = parseFloat($("#total_ttc").text());
        var total_ht = parseFloat($("#total_ht").text());
        var total_ttva = parseFloat($("#total_ttva").text());
        var modePaiment = $('select[name="reglement"]').val();
        var num_order = $("#check_ref").val();
        var comment = $("#commentReg").val();
        var montantPayer = parseFloat($("#montantPayer").val());

        reg.document_id = document_id;
        reg.parent_id = parent_id;
        reg.mode_reg = modePaiment;
        reg.num_order = num_order;
        reg.comment = comment;
        reg.amount_reg = montantPayer;
        var dateObj = new Date();
        // Formate la date selon le format YYYY-MM-DD HH:MM:SS
        reg.date_reg = dateObj.toISOString().slice(0, 19).replace('T', ' ');
        if (modePaiment !== "" && comment !=="") {

             if (montantPayer + montantTotalPayer <= total_ttc && montantPayer > 0) {
                 var total_restanter = total_ttc - (montantPayer + montantTotalPayer);
                 $("#total_restanter").text(total_restanter.toFixed(2));
                 if (total_restanter === 0) {
                     $('#montantPayer').val(0);
                     $('#montantPayer').prop('readonly', true);
                 }
                 data.push(reg);
                 // reglements.push(reg);
                 localStorage.setItem("product_commandEdit", JSON.stringify(commande));
                 // console.log(total_restanter);
                 tableReglements();
                 $('#montantPayer').val(0);
                 $('#commentReg').val("");
                 $('#check_ref').val("");
                 $('select[name="reglement"]').val("").trigger('change.select2'); // Déselectionner l'option
             } else {
                 console.log("le montant entrer est plus grand que le reste à payer !");
                 Swal.fire(
                     "Super!",
                     "Le montant saisi n'est as valide ou plus grand que le reste à payer",
                     "error"
                 );
             }

        } else {
            Swal.fire(
                "Super!",
                "Veuillez choisir le mode de paiement et saisir un commentaire",
                "error"
            );
       }
        console.log("Action du bouton Valider");
    } else if ($(this).attr("id") === "btnModifier") {
        // Réafficher tous les boutons "delete" cachés
        $(".deleteReglement.hidden").show();
        // Récupérer l'index de l'élément à mettre à jour
        let index = $("#index").val();
        let montantTotalPayer = 0;
        let montantEdit = localStorage.getItem('montantEdit');
        // Récupérer les données actuelles depuis le localStorage
        let object = JSON.parse(localStorage.getItem("product_commandEdit"));
        let data = object.detailsReglement;
        let commande = object.object;
        if (data && data.length > 0) {
            // Calcule le prix hors taxe
            montantTotalPayer = data.reduce(
                (acc, reg) => acc + reg.amount_reg,
                0
            );
        }
        // Vérifier que l'index est valide
        if (index >= 0 && index < data.length) {
            // Récupérer les nouvelles valeurs du formulaire
            let modePaiment = $('select[name="reglement"]').val();
            let num_order = $("#check_ref").val();
            if (modePaiment !== 'Cheque') {
                num_order = "";
            }
            let comment = $("#commentReg").val();
            let montantPayer = parseFloat($("#montantPayer").val());
            let total_restanter = parseFloat($("#total_restanter").text());
            let total_ttc = parseFloat($("#total_ttc").text());


            if (montantPayer + montantTotalPayer - montantEdit <= total_ttc && montantPayer > 0) {
                total_restanter = total_ttc - (montantPayer + montantTotalPayer - montantEdit);
                $("#total_restanter").text(total_restanter.toFixed(2));
                if (total_restanter === 0) {
                    $('#montantPayer').val(0);
                    $('#montantPayer').prop('readonly', true);
                }
            }

            // Mettre à jour les valeurs de l'élément sélectionné
            data[index].mode_reg = modePaiment;
            data[index].num_order = num_order;
            data[index].comment = comment;
            data[index].amount_reg = montantPayer;
            commande.total_restant = total_restanter;
            commande.total_payant = montantTotalPayer;

            // Mettre à jour le localStorage avec les données mises à jour
            localStorage.setItem("product_commandEdit", JSON.stringify(object));

            // Actualiser la vue
            tableReglements();

            // Effacer les champs du formulaire ou effectuer d'autres actions nécessaires
            $('#montantPayer').val(0);
            $('#total_restanter').val(total_restanter);
            $('#commentReg').val("");
            $('#check_ref').val("");
            $('select[name="reglement"]').val("").trigger('change.select2');
            $(this).attr("id", "btnValider");
        } else {
            console.log("Index invalide !");
        }
        console.log("Action du bouton Modifier");
    }
});

$(".storeReglement").on("click", function (e) {
    e.preventDefault();

    // let formData = new FormData($("#commands_form")[0]);
    var data = JSON.parse(localStorage.getItem("product_commandEdit")) || [];
    var commandId = data.object.id;
    var listeReg = data.detailsReglement;
    var postData = {
        reglements: listeReg,
        reg_code: $('#reg_code').text(),
        command_id: commandId
    };
    console.log(postData);
    localStorage.setItem("postData", JSON.stringify(postData));
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/reglements",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function (response) {
            if (response.success) {
                localStorage.removeItem("product_commandEdit");
                // $('#productTableBody').empty();
                data.detailsReglement = [];
                getCommandDetails(commandId);
                Swal.fire(
                    "Super!",
                    "Réglement a été créé avec succès",
                    "success"
                );
                location.reload();
                console.log("delete storage");
                loadTableFromLocalStorage();

                // localStorage.setItem("product_commandEdit", JSON.stringify(data));


            }
        },
    });
});


$(".updateReglement").on("click", function (e) {
    e.preventDefault();
    $("#storeReglement").show();
    $("#updateReglement").hide();
    var data = JSON.parse(localStorage.getItem("product_commandEdit")) || {};
    var commandId = data.object.id;
    var listeReg = data.detailsReglement;
    var postData = {
        reglements: listeReg,
    };
    console.log(postData);
    localStorage.setItem("postData", JSON.stringify(postData));

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        url: "/reglements/" + commandId,
        type: "PUT",
        data: JSON.stringify(postData), // Convertir postData en JSON
        contentType: "application/json", // Spécifier le type de contenu comme JSON
        dataType: "json",
        success: function (data) {
            if (data.success) {

                localStorage.removeItem("product_commandEdit");
                // $('#productTableBody').empty();
                data.detailsReglement = [];
                getCommandDetails(commandId);
                Swal.fire(
                    "Super!",
                    "Réglement a été modifié avec succès",
                    "success"
                );
                location.reload();
                console.log("delete storage");
                loadTableFromLocalStorage();
            }
        },
    });
});
