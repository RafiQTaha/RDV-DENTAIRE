/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";
import "@tabler/core/src/scss/tabler.scss";

// tabler
require("@tabler/core/dist/js/demo.min");
require("@tabler/core/dist/js/demo-theme.min");
require("@tabler/core/dist/js/tabler.min");
require("@tabler/core/dist/js/tabler.esm.min");

import 'filepond/dist/filepond.min.css';

// Import FilePond
import * as FilePond from 'filepond';
import FilePondPluginFileEncode from 'filepond-plugin-file-encode';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileMetadata from 'filepond-plugin-file-metadata';

FilePond.registerPlugin(
  FilePondPluginFileEncode,
  FilePondPluginFileValidateSize,
  FilePondPluginImageExifOrientation,
  FilePondPluginImagePreview,
  FilePondPluginFileMetadata
);

FilePond.setOptions({
  labelIdle: 'Glissez & déposez vos fichiers ou <span class="filepond--label-action"> Parcourir </span>',
  labelInvalidField: 'Le champ contient des fichiers invalides',
  labelFileWaitingForSize: 'En attente de taille',
  labelFileSizeNotAvailable: 'Taille non disponible',
  labelFileLoading: 'Chargement',
  labelFileLoadError: 'Erreur lors du chargement',
  labelFileProcessing: 'Téléversement',
  labelFileProcessingComplete: 'Téléversement terminé',
  labelFileProcessingAborted: 'Téléversement annulé',
  labelFileProcessingError: 'Erreur lors du téléversement',
  labelFileProcessingRevertError: 'Erreur lors de l’annulation',
  labelFileRemoveError: 'Erreur lors de la suppression',
  labelTapToCancel: 'Appuyez pour annuler',
  labelTapToRetry: 'Appuyez pour réessayer',
  labelTapToUndo: 'Appuyez pour annuler',
  labelButtonRemoveItem: 'Supprimer',
  labelButtonAbortItemLoad: 'Annuler',
  labelButtonRetryItemLoad: 'Réessayer',
  labelButtonAbortItemProcessing: 'Annuler',
  labelButtonUndoItemProcessing: 'Annuler',
  labelButtonRetryItemProcessing: 'Réessayer',
  labelButtonProcessItem: 'Téléverser',
  labelMaxFileSizeExceeded: 'Le fichier est trop volumineux',
  labelMaxFileSize: 'La taille maximale du fichier est {filesize}',
  labelMaxTotalFileSizeExceeded: 'La taille totale maximale des fichiers dépassée',
  labelMaxTotalFileSize: 'La taille totale maximale des fichiers est {filesize}',
  labelFileTypeNotAllowed: 'Type de fichier non autorisé',
  fileValidateTypeLabelExpectedTypes: 'Attente {allButLastType} ou {lastType}',
  imageValidateSizeLabelFormatError: 'Type d’image non supporté',
  imageValidateSizeLabelImageSizeTooSmall: 'L’image est trop petite',
  imageValidateSizeLabelImageSizeTooBig: 'L’image est trop grande',
  imageValidateSizeLabelExpectedMinSize: 'La taille minimale est de {minWidth} × {minHeight}',
  imageValidateSizeLabelExpectedMaxSize: 'La taille maximale est de {maxWidth} × {maxHeight}',
  imageValidateSizeLabelImageResolutionTooLow: 'La résolution est trop faible',
  imageValidateSizeLabelImageResolutionTooHigh: 'La résolution est trop élevée',
});

window.FilePond = FilePond;

// file sizing
import bytes from 'bytes';

window.bytes = bytes;

// jquery
const $ = require("jquery");
global.$ = global.jQuery = $;
require("jqueryui");

// axios
import axios from "axios";
global.axios = axios;

// moment
const moment = require("moment");
global.moment = moment;
require("./components/includes/moment.js");

// swal
const Swal = require("./components/includes/sweetalert2");
global.Swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer);
    toast.addEventListener("mouseleave", Swal.resumeTimer);
  },
});
global.Toast = Toast;
const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-white mr-2 btn sySweetStyle",
    cancelButton: "btn btn-warning btn sySweetStyle",
  },
  buttonsStyling: false,
});

global.swalWithBootstrapButtons = swalWithBootstrapButtons;

// smooth scroll
const SmoothScroll = require("./components/includes/smooth-scroll.polyfills.min.js");
global.SmoothScroll = SmoothScroll;

// fos routing
import Routing from "fos-router";
window.Routing = Routing;

// excel
const XLSX = require("xlsx");
global.XLSX = XLSX;

// bootstrap
require("bootstrap/dist/js/bootstrap.bundle");

// fontawesome
require("@fortawesome/fontawesome-free/css/all.css");

// select2
require("select2");
require("select2/dist/css/select2.css");
require("./components/includes/select2language/fr.js");
$(".select").select2();

// datatables
require("./components/includes/datatables/core");
// require("./components/includes/datatables/datatable-bs4");
require("datatables.net-bs5");
const datatablesFrench = require("./components/includes/datatables_french.json");
global.datatablesFrench = datatablesFrench;

// ladda
require("ladda/dist/ladda.min.css");
import * as Ladda from "ladda";
global.Ladda = Ladda;

// notyf
import { Notyf } from "notyf";
import "notyf/notyf.min.css";
const notyf = new Notyf({
  position: {
    x: "right",
    y: "top",
  },
  duration: 5000,
  types: [
    {
      type: "info",
      background: "#0948B3",
      icon: {
        className: "fas fa-info-circle",
        tagName: "span",
        color: "#fff",
      },
    },
    {
      type: "warning",
      background: "#F5B759",
      icon: {
        className: "fas fa-exclamation-triangle",
        tagName: "span",
        color: "#fff",
      },
    },
  ],
  dismissible: true,
});
// global.notyf = notyf;
window.notyf = notyf;

// show password

$("#showPassword").on("click", function (e) {
  if ("password" == $("#inputPassword").attr("type")) {
    $("#inputPassword").prop("type", "text");
  } else {
    $("#inputPassword").prop("type", "password");
  }
});

$(".theme-toggle").on("click", function (e) {
  const currentTheme = $('body').attr('data-bs-theme') === 'dark' ? 'dark' : 'light';
  const newTheme = currentTheme === 'dark' ? '' : 'dark';
  $('body').attr('data-bs-theme', newTheme);
  localStorage.setItem('tablerTheme', newTheme);
});

$(".navbar-toggler").on("click", function (e) {
  e.preventDefault();
  $(this).toggleClass("opened");
  $('#sidebar-menu').toggleClass('show');
});



// Optionally, load the theme preference on page load
$(document).ready(function () {
  const savedTheme = localStorage.getItem('tablerTheme');
  if (savedTheme) {
    $('body').attr('data-bs-theme', savedTheme);
  }
});

// reset password
$("#password-change").on("click", function (e) {
  $("#modal-changer-mdp").modal('show')
});

$('#modal-changer-mdp #reset_form').on('submit', async function (e) {
  e.preventDefault();
  console.log('gu');

  var formData = new FormData($(this)[0])
  $("#btn-reset").find("i").removeClass("fa-rotate").addClass("fa-spinner fa-spin");
  try {
    const url = Routing.generate("app_reset_password"); // Generate the URL separately

    // Send the form data with axios
    const response = await axios.post(url, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    window.location.href = "/logout";
  } catch (error) {
    console.log(error);
    if (error.response && error.response.data) {
      const message = error.response.data;
      notyf.error(message);
    } else {
      notyf.error('Something went wrong!');
    }
  }
})

const now = new Date();

const year = now.getFullYear();
const month = String(now.getMonth() + 1).padStart(2, '0');
const day = String(now.getDate()).padStart(2, '0');
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');

const formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`;
console.log(formattedDate);

$('body .datepicker').attr('value', formattedDate);
