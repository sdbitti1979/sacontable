import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es.js';
import 'flatpickr/dist/flatpickr.css';

document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#datetimepicker", {
        locale: Spanish,
        dateFormat: "Y-m-d", // Formato de fecha
    });
});


import './bootstrap';

import jQuery from 'jquery';
window.$ = jQuery;

import swal from 'sweetalert2';
window.Swal = swal;

window.activarFiltro = function (filtro, url) {
    $("#" + filtro).on('input', function() {
        var valor = $(this).val();

        // Hacer una solicitud AJAX al servidor
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                codigo: valor // Enviar el valor del código como parámetro
            },
            success: function(response) {
                // Procesar los resultados y mostrarlos (puedes usar un select o una lista)

                if (response.length > 0) {
                    // Asignar el resultado al campo "cuentaPadre"
                    $("#" + filtro).val(response[0].nombre); // Primer resultado (puedes ajustar según lo que desees mostrar)
                } else {
                    $("#" + filtro).val(''); // Si no hay resultados, limpiar el campo
                }
            },
            error: function() {
                console.log("Error al cargar los datos.");
            }
        });
    });
}





