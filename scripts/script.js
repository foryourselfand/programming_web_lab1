"use strict";

let
    X,
    Y,
    R,
    answerValues = document.getElementById("answerValues"),
    pointer = document.getElementById("pointer");


function checkY() {
    let Y_text = document.getElementById('Y-text');
    Y = Y_text.value.replace(',', '.')
    if (Y.trim() === "") {
        Y_text.setCustomValidity("Заполните поле");
        return false;
    } else if (!isFinite(Y)) {
        Y_text.setCustomValidity("Должно быть числом");
        return false;
    } else if (Y >= 5 || Y <= -5) {
        Y_text.setCustomValidity("Должно быть в диапазоне (-5; 5)");
        return false;
    }
    return true;
}

function setX() {
    X = document.getElementById('X-select').value;
}

function setR() {
    let R_group = document.getElementsByName("R-radio");
    for (let i = 0; i < R_group.length; i++)
        if (R_group[i].checked) {
            R = R_group[i].value;
            break;
        }
}

function setPointer() {
    pointer.style.visibility = "visible";
    pointer.setAttribute("cx", (X / R * 2 * 60 + 150).toString());
    pointer.setAttribute("cy", (-Y / R * 2 * 60 + 150).toString());
}


const submit = function (e) {
    if (!(checkY())) return;

    e.preventDefault();

    setX();
    setR();

    let request = '?x=' + X + '&y=' + Y + '&r=' + R;
    fetch("scripts/check.php" + request, {
        method: "GET",
        headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"},
    }).then(response => response.json()).then(function (serverAnswer) {
        setPointer();

        let result = "<tr>";
        for (let [index, row] of Object.entries(serverAnswer).reverse()) {
            for (let [key, value] of Object.entries(row)) {
                if (key === "coordsStatus") {
                    let color;
                    if (value === true)
                        color = "green"
                    else
                        color = "red"
                    value = "<span style='color: " + color + "'>" + value + "</span>";
                }

                result += "<td>" + value + "</td>"
            }
            result += "</tr>";
        }

        answerValues.innerHTML = result;
    }).catch(err => console.log(err));
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('submitButton').addEventListener('click', submit);
});

document.getElementById("clearButton").onclick = function () {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'scripts/clear.php');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            answerValues.innerHTML = "";
        }
    }
    xhr.send()
}