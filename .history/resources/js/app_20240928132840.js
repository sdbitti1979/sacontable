import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es.js';
import 'flatpickr/dist/flatpickr.css';

document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#datetimepicker", {
        locale: Spanish,
        dateFormat: "Y-m-d", // Formato de fecha
    });
});

document.addEventListener('cerrarModal', function () {
    var myModal = bootstrap.Modal.getInstance(document.getElementById('ajaxModal'));
    if (myModal) {
        myModal.hide();
    }
});
import './bootstrap';

import jQuery from 'jquery';
window.$ = jQuery;

import swal from 'sweetalert2';
window.Swal = swal;







