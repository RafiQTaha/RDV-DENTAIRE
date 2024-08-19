
$(document).ready(function () {
    $(".block_page").html('Synchronisation')

    $("body .synchnisation_academique").on("click", async function (e) {
        e.preventDefault();
        syncEtablissement()
        $('.synchnisation_academique').addClass('disabled')

    });
    $("body .synchnisation_etudiant").on("click", async function (e) {
        e.preventDefault();
        syncEtudiant()
        $('.synchnisation_etudiant').addClass('disabled')

    });
    $("body .synchnisation_comptes").on("click", async function (e) {
        e.preventDefault();
        $('.synchnisation_comptes').addClass('disabled')
        synCompte()
        // alert('comptes')

    });



    const synCompte = async () => {
        const icon = $(".syn_compte");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_compte'));
            const response = request.data;
            $('.syn_compte_content').text(' ( Crée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            // $('.syn_compte_content').text(' ('+response+')').css('color','green')
            $('.synchnisation_academique').removeClass('disabled')
        } catch (error) {
            const message = error.response.data;
            $('.syn_compte_content').text('Erreur lors du création des comptes..!!').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }









    const syncEtudiant = async () => {
        const icon = $(".syn_t_etudiant");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_etudiant'));
            const response = request.data;
            $('.syn_t_etudiant_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncPreinscription()
        } catch (error) {
            const message = error.response.data;
            $('.syn_t_etudiant_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }
    const syncPreinscription = async () => {
        const icon = $(".syn_t_preinscription");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_preinscription'));
            const response = request.data;
            $('.syn_t_preinscription_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncAdmission()
        } catch (error) {
            const message = error.response.data;
            $('.syn_t_preinscription_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }
    const syncAdmission = async () => {
        const icon = $(".syn_t_admission");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_admission'));
            const response = request.data;
            $('.syn_t_admission_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncStatut()
        } catch (error) {
            const message = error.response.data;
            $('.syn_t_admission_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }
    const syncStatut = async () => {
        const icon = $(".syn_p_statut");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_statut'));
            const response = request.data;
            $('.syn_p_statut_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncGroupe()
        } catch (error) {
            const message = error.response.data;
            $('.syn_p_statut_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }
    const syncGroupe = async () => {
        const icon = $(".syn_p_groupe");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_groupe'));
            const response = request.data;
            $('.syn_p_groupe_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncInscription()
        } catch (error) {
            const message = error.response.data;
            $('.syn_p_groupe_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }
    const syncInscription = async () => {
        const icon = $(".syn_t_inscription");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_inscription'));
            const response = request.data;
            $('.syn_t_inscription_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            $('.synchnisation_comptes').removeClass('disabled btn-secondary').addClass('btn-primary')
        } catch (error) {
            const message = error.response.data;
            $('.syn_t_inscription_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }











    const syncEtablissement = async () => {
        const icon = $(".syn_ac_etablissement");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_etablissement'));
            const response = request.data;
            $('.syn_ac_etablissement_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncFormation()
        } catch (error) {
            const message = error.response.data;
            $('.syn_ac_etablissement_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }

    const syncFormation = async () => {
        const icon = $(".syn_ac_formation");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_formation'));
            const response = request.data;
            $('.syn_ac_formation_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncAnnee()
        } catch (error) {
            const message = error.response.data;
            $('.syn_ac_formation_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }

    const syncAnnee = async () => {
        const icon = $(".syn_ac_annee");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_annee'));
            const response = request.data;
            $('.syn_ac_annee_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncPromotion()
        } catch (error) {
            const message = error.response.data;
            $('.syn_ac_annee_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }

    const syncPromotion = async () => {
        const icon = $(".syn_ac_promotion");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_promotion'));
            const response = request.data;
            $('.syn_ac_promotion_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            syncSemestre()
        } catch (error) {
            const message = error.response.data;
            $('.syn_ac_promotion_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }

    const syncSemestre = async () => {
        const icon = $(".syn_ac_semestre");
        icon.removeClass("fa-edit").addClass("fa-spinner fa-spin");
        try {
            const request = await axios.post(Routing.generate('api_semestre'));
            const response = request.data;
            $('.syn_ac_semestre_content').text(' (Ajoutée: ' + response.newRow + ' Modifiée: ' + response.updated + ')').css('color', 'green')
            $('.synchnisation_etudiant').removeClass('disabled btn-secondary').addClass('btn-primary')
            // syncsemestre()
        } catch (error) {
            const message = error.response.data;
            $('.syn_ac_semestre_content').text('(' + message + ')').css('color', 'red')
        }
        icon.addClass("fa-edit").removeClass("fa-spinner fa-spin");
    }

})


