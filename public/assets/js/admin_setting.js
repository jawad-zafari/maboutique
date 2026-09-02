"use strict";

document.addEventListener("DOMContentLoaded", () => {
    
    function setupColorPicker(inputId) {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener('click', () => {
                if (inputElement.jscolor) {
                    inputElement.jscolor.show();
                }
            });
        }
    }

    setupColorPicker('bodyColor');
    setupColorPicker('menuColor');

    
});