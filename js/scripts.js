/*global document*/
"use strict";
(function() {
    var elementos = document.getElementsByClassName("borrar");
    for (var i = 0; i < elementos.length; i++) {
        var elemento = elementos[i];
        elemento.addEventListener("click", function(event) {
            if (!confirm("¿Borrar?")) {
                event.preventDefault();
            }
        }, false);
    }
}());