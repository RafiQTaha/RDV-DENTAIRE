$(document).ready(function () {
    var previousXhr = null;

    $("select").select2();

    let selectedActes = [];

    if ($.fn.dataTable.isDataTable("#list_rendezvous")) {
        $("#list_rendezvous").DataTable().clear().destroy();
    }

    let filterValue = "all";
    $('.filter-radio').on('change', function () {
        filterValue = $(this).val();
        table.ajax.reload();
    });

    var table = $("#list_rendezvous").DataTable({
        lengthMenu: [
            [10, 15, 25, 50, 100, 20000000000000],
            [10, 15, 25, 50, 100, "All"],
        ],
        order: [[0, "desc"]],
        ajax: {
            url: Routing.generate("app_admin_rdv_listing_list"),
            type: "get",
            data: function (d) {
                d.filterDate = filterValue;
            },
            beforeSend: function (jqXHR) {
                if (previousXhr) {
                    previousXhr.abort();
                }
                previousXhr = jqXHR;
            },
        },
        processing: true,
        serverSide: true,
        deferRender: true,
        responsive: true,
        columns: [
            { name: "r.id", data: "id" },
            { name: "r.admCode", data: "admCode" },
            { name: "etu.nom", data: null },
            { name: "r.Code", data: "Code" },
            { name: "r.nom", data: null },
            { name: "r.cin", data: "cin" },
            { name: "r.acts", data: "acts" },
            { name: "r.date", data: "date" },
            { name: "r.created", data: "created" },
            { orderable: false, searchable: false, data: "id" },
        ],
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false,
                render: function (data, type, full, meta) {
                    return meta.row + 1; // Custom order starting from 1
                }
            },
            {
                // Full name for etu (student)
                targets: 2,
                render: function (data, type, full, meta) {
                    return full.nomEtu + ' ' + full.prenomEtu;
                }
            },
            {
                // Full name for patient
                targets: 4,
                render: function (data, type, full, meta) {
                    return full.nom + ' ' + full.prenom;
                }
            },
            {
                targets: 6,
                render: function (data, type, full, meta) {
                    if (data) {
                        return `<span id="truncated-text" title="${data}">${data.length > 40 ? data.substring(0, 40) + "..." : data}</span>`;
                    }
                    return data;
                }
            },
            {
                targets: 7,
                render: function (data, type, full, meta) {
                    return window.moment(data.date).format('YYYY-MM-DD HH:mm:ss');
                },
            },
            {
                targets: 8,
                render: function (data, type, full, meta) {
                    return window.moment(data.date).format('YYYY-MM-DD HH:mm:ss');
                },
            },
            {
                targets: 9,
                render: function (data, type, full, meta) {
                    return `
                        <div class="dropdown" style="">
                            <svg class="icon" fill="black" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <circle cx="12" cy="5" r="2"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                                <circle cx="12" cy="19" r="2"></circle>
                            </svg>
                            <div class="actions dropdown-menu dashboard-dropdown dropdown-menu-center mt-2 py-1">
                                <a href="#" data-id="${full.id}" class="dropdown-item detailsRdv"><i class="fa fa-eye"></i>&nbsp; Détails</a>
                            </div>
                        </div>
                    `;
                },
            },
        ],
        language: datatablesFrench,
        initComplete: function () {
            // Prevent sorting when interacting with select in header
            $("thead .selection").on("click", function (e) {
                e.stopPropagation();
            });
        },
    });

    $('body').on('click', '.detailsRdv', async function (e) {
        e.preventDefault();
        let id_rdv = $(this).attr('data-id');
        $('#detailsModal').modal("show")
        try {
            const request = await axios.post(
                Routing.generate('app_admin_rdv_listing_details', { rendezvous: id_rdv })
            );
            const response = await request.data;
            $("#detailsModal #detailsBody").html("");
            $("#detailsModal #detailsBody").html(response["detailsRdv"]);

            const pieces = response.pieces;

        } catch (error) {
            window.notyf.dismissAll();
            console.log(error);
            if (error.response && error.response.data) {
                const message = error.response.data;
                window.notyf.error(message);
            } else {
                window.notyf.error('Something went wrong!');
            }
        }
    })

    $(".export").on("click", () => {
        $("#export_modal").modal("show");
    });

    $('body').on('click', '#exportExcel', function (e) {
        e.preventDefault();
        let dateDebut = $("#dateDebut").val();
        let dateFin = $("#dateFin").val();

        let url = Routing.generate('app_admin_rdv_listing_export_excel');

        if (dateDebut && dateFin) {
            url += `?dateDebut=${encodeURIComponent(dateDebut)}&dateFin=${encodeURIComponent(dateFin)}`;
        } else if (dateDebut) {
            url += `?dateDebut=${encodeURIComponent(dateDebut)}`;
        } else if (dateFin) {
            url += `?dateFin=${encodeURIComponent(dateFin)}`;
        }

        window.open(url, '_blank');
    })

    $('body').on('click', '#exportPDF', function (e) {
        e.preventDefault();
        let dateDebut = $("#dateDebut").val();
        let dateFin = $("#dateFin").val();

        let url = Routing.generate('app_admin_rdv_listing_export_pdf');

        if (dateDebut && dateFin) {
            url += `?dateDebut=${encodeURIComponent(dateDebut)}&dateFin=${encodeURIComponent(dateFin)}`;
        } else if (dateDebut) {
            url += `?dateDebut=${encodeURIComponent(dateDebut)}`;
        } else if (dateFin) {
            url += `?dateFin=${encodeURIComponent(dateFin)}`;
        }

        window.open(url, '_blank');
    })

});
