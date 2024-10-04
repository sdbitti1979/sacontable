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
        var valor = $(this).val().trim();

        if (valor === null || valor === "") {
            $("#resultados").empty();
            return; // No continuar con la solicitud AJAX
        }
        // Hacer una solicitud AJAX al servidor
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                codigo: valor // Enviar el valor del código como parámetro
            },
            success: function(response) {
                // Procesar los resultados y mostrarlos (puedes usar un select o una lista)
                var resultados = $("#resultados");
                resultados.empty();
                $("#resultados").attr("display", "block");
                $.each(response, function(index, item) {
                    resultados.append('<option value="' + item.codigo + '">' + item.nombre + '</option>');
                });

            },
            error: function() {
                console.log("Error al cargar los datos.");
            }
        });
    });
}





