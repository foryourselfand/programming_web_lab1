"use strict";

let
    X = document.getElementById('X-select'),
    Y = document.getElementById('Y-text'),
    R_group = document.getElementsByName("R-radio"),
    R_value,
    answerValues = document.getElementById("answerValues"),
    pointer = document.getElementById("pointer");


function checkY() {
    if (Y.value.trim() === "") {
        Y.setCustomValidity("Заполните поле");
        return false
    } else if (!isFinite(Y.value)) {
        Y.setCustomValidity("Должно быть числом");
        return false
    } else if (Y.value >= 5 || Y.value <= -5) {
        Y.setCustomValidity("Должно быть в диапазоне (-5; 5)");
        return false
    } else return true
}

function proceedRValue() {
    for (let i = 0; i < R_group.length; i++)
        if (R_group[i].checked) {
            R_value = R_group[i].value;
            break;
        }
}

function setPointer() {
    pointer.style.visibility = "visible";
    pointer.setAttribute("cx", (X.value / R_value * 2 * 60 + 150).toString());
    pointer.setAttribute("cy", (-Y.value / R_value * 2 * 60 + 150).toString());
}


const submit = function (e) {
    if (!(checkY())) return

    e.preventDefault();

    proceedRValue()

    let request = '?x=' + X.value + '&y=' + Y.value + '&r=' + R_value;
    fetch("checkk.php" + request, {
        method: "GET",
        headers: {"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"},
    }).then(response => response.text()).then(function (serverAnswer) {
        answerValues.innerHTML = serverAnswer;
        setPointer();
    }).catch(err => console.log(err));
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('submitButton').addEventListener('click', submit);
});

document.getElementById("clearButton").onclick = function () {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'clearr.php')

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            answerValues.innerHTML = xhr.responseText;
        }
    }
    xhr.send()
}