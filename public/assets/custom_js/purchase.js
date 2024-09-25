$(document).ready(function () {
    const tableBody = $("#productTableBody");

    // Call this function to initialize the table with data from localStorage
    loadTableFromLocalStorage();
    // Existing code...

    function loadTableFromLocalStorage() {
        console.log("Loading table from localStorage");

        var listeProd = JSON.parse(localStorage.getItem("product_purchase")) || [];
        tableBody.empty(); // Clear the existing content in the table

        // Iterate over the products and add them to the table
        listeProd.forEach((product) => {
            const productHTML = generateProductHTML(product);
            tableBody.append(productHTML);
        });
    }

    var newProduit = {
        ref: "",
        designation: "",
        unite: "",
        quantite: 0,

    };

    $(".getProduct").on("click", function () {
        var product_id = $('select[name="product_id"]').val();
        console.log(product_id);
        if (product_id) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: window.location.origin + "/products/get-product",
                data: {
                    id: product_id,
                },
                type: "GET",
                dataType: "json",
                success: function (data) {
                    var prod = objectProd(data);
                    pushProdToListe(prod);
                    tableProducts();
                },
            });
        } else {
            console.log("AJAX load did not work");
        }
    });

    function objectProd(data) {
        var p = Object.assign({}, newProduit);
        p.ref = data.product_code;
        p.id = data.id;
        p.designation = data.name_fr + " | " + data.name_ar;
        p.unite = data.unite;
        p.quantite = 1;

        return p;
    }

    function pushProdToListe(prod) {
        var listeProd = JSON.parse(localStorage.getItem("product_purchase")) || [];

        // Check if the product already exists in the list
        var existingProduct = listeProd.find(
            (product) => product.id === prod.id
        );

        if (existingProduct) {
            existingProduct.quantite += 1;

        } else {
            listeProd.push(prod);
        }

        localStorage.setItem("product_purchase", JSON.stringify(listeProd));
    }

    function tableProducts() {
        var listeProd = JSON.parse(localStorage.getItem("product_purchase")) || [];

        tableBody.empty();

        listeProd.forEach((product) => {
            const productHTML = generateProductHTML(product);
            tableBody.append(productHTML);
        });
    }

    // Sélectionnez le conteneur où vous souhaitez ajouter les lignes (par exemple, un tbody avec l'id "productTableBody").
    // const tableBody = document.getElementById('productTableBody');
    var counter = 0;
    // Fonction pour générer le HTML pour chaque produit
    function generateProductHTML(product) {
        const tr = $("<tr>").addClass("text-center");
        counter++;

        // Properties to include in the td cells
        const propertiesToInclude = [
            "ref",
            "designation",
            "unite",
            "quantite",
        ];

        // Iterate over properties to include and create td elements
        propertiesToInclude.forEach((key) => {
            const td = $("<td>");

            if (key === "quantite") {
                // Create quantity div with buttons
                const quantityDiv = $("<div>").addClass("input-step");

                // Create minus button
                const minusButton = $("<button>")
                    .addClass("minus")
                    .prop("type", "button")
                    .text("–")
                    .on("click", () => decrease(product.id, "quantite"));

                // Create quantity input
                const qteInput = document.createElement("input");
                qteInput.type = "number";
                qteInput.classList.add("product-quantity");
                qteInput.id = `product-qty-${product.id}`;
                qteInput.value = product.quantite;
                qteInput.addEventListener("blur", function () {
                    // Récupère l'élément input actuel
                    const input = $(this);
                    // Récupère l'id de l'élément input
                    const id = input.attr("id");
                    // Appelez la fonction increase en passant l'ID du produit
                    const idProd = id.slice(12);
                    if (qteInput.value < 1) {
                        qteInput.value = 1;
                    }

                    tableProducts();
                });

                // Create plus button
                const plusButton = $("<button>")
                    .addClass("plus")
                    .prop("type", "button")
                    .text("+")
                    .on("click", () => increase(product.id, "quantite"));

                // Append buttons and input to the div
                quantityDiv.append(minusButton, qteInput, plusButton);

                // Append the div to the td element
                td.append(quantityDiv);
            }

            tr.append(td);
        });

        // Create remove button
        const removeButton = $("<button>")
            .addClass("btn")
            .prop("type", "button")
            .html('<i class="las la-times text-danger fs-1"></i>')
            .on("click", () => deleteProduct(product.id));

        const tdRemove = $("<td>").append(removeButton);
        tr.append(tdRemove);

        return tr;
    }

    function increase(id, type) {
        var qteInput = $(`#product-qty-${id}`);
        var qte = parseFloat(qteInput.val());
        if (type === "quantite") {
            if (!isNaN(qte)) {
                qte += 1;
                qteInput.val(qte);


                tableProducts();
            } else {
                console.error(`La quantité n'est pas un nombre valide.`);
            }
        }
            if (!isNaN(tva)) {
                // Update the tva in the input
                tva += 1;
                tvaInput.val(tva);
                tableProducts();
            } else {
                console.error(`Le tva n'est pas un nombre valide.`);
            }

    }

    function decrease(id, type) {
        var qteInput = document.getElementById(`product-qty-${id}`);
        var qte = parseFloat(qteInput.value);

        if (type === 'quantite') {
            if (!isNaN(qte) && qte > 1) {
                qte -= 1;

                // Update the quantity in the input field
                qteInput.value = qte;


                tableProducts();

            } else {
                console.error("Invalid quantity or quantity cannot be decreased further.");
                return 1;
            }
        }
    }



    function deleteProduct(productId) {
        // e.preventDefault();
        // Récupérer les produits actuels depuis le localStorage
        var listeProd = JSON.parse(localStorage.getItem("product_purchase"));

        // Vérifier s'il y a des produits dans le localStorage
        if (listeProd) {
            // Trouver l'index du produit avec l'ID donné
            var productIndex = listeProd.findIndex(function (product) {
                return product.id === productId;
            });

            // Vérifier si le produit a été trouvé
            if (productIndex !== -1) {
                // Supprimer le produit de la liste des produits
                listeProd.splice(productIndex, 1);

                // Mettre à jour le localStorage avec la nouvelle liste de produits
                localStorage.setItem(
                    "product_purchase",
                    JSON.stringify(listeProd)
                );
                console.log("Produit supprimé avec succès.");
            } else {
                console.log("Produit non trouvé.");
            }
        } else {
            console.log("Aucun produit trouvé dans le localStorage.");
        }
        tableProducts();
    }

    $(".storeDevis").on("click", function (e) {
        e.preventDefault();

        let formData = new FormData($("#formAddDevis")[0]);
        data = {
            supplier: $('select[name="supplier_id"]').val(),
            category: $('select[name="category_id"]').val(),
            scategory: $('select[name="scategory_id"]').val(),
            status: $('select[name="status"]').val(),
            status_date: $('input[name="status_date"]').val(),
            comment: $('textarea[name="comment"]').val(),
            num_purchase: $("#num_purchase").text(),
            products: JSON.parse(localStorage.getItem("product_purchase")),
        };
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            url: "/devis",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (data) {
                if (data.errors) {
                    $.each(data.errors, function(field, error) {
                        $('#'+field+'-error').text((error)[0]);
                    });
                }

                if (data.success) {
                    localStorage.removeItem("product_purchase");
                    loadTableFromLocalStorage();
                    location.reload();
                    console.log("delete storage");

                    Swal.fire(
                        "Super!",
                        "Devis a été créé avec succès",
                        "success"
                    );
                    window.location.href = '/devis/'+data.id;
                    // window.location.href = '/devis';

                }
            },
        });
    });
});
