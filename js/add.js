var add = document.querySelector('.add_pr');
var btn = document.querySelector('.btn-info');
function addActive() {
    add.classList.add("active_add");
    btn.classList.add("active_btn");
}

function removeActive() {
    add.classList.remove("active_add")
}

var added = document.querySelector("added_pr");
function added_pr(){
    added.classList.add("active_added")
}
var add_sup = document.querySelector(".add_sup")
var btn_sup = document.getElementById('btn-sup');
function active_sup(){
    add_sup.classList.add('active-sup');
    btn_sup.classList.add('active_btn')
}
function cancel_added_pr() {
    add.classList.remove("active_add")
}