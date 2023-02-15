e = true;

function changer() {
    if (e) {
        document.getElementById("pass").setAttribute("type","text");
        e = false;
    } else {
        document.getElementById("pass").setAttribute("type","password");
        e = true;
    }
}

function changerLogIn() {
    if (e) {
        document.getElementById("pass").setAttribute("type","text");
        document.getElementById("pass2").setAttribute("type","text");
        e = false;
    } else {
        document.getElementById("pass").setAttribute("type","password");
        document.getElementById("pass2").setAttribute("type","password");
        e = true;
    }
}

function changerCompte() {
    if (e) {
        document.getElementById("pass").setAttribute("type","text");
        document.getElementById("pass2").setAttribute("type","text");
        document.getElementById("pass1").setAttribute("type","text");
        e = false;
    } else {
        document.getElementById("pass").setAttribute("type","password");
        document.getElementById("pass2").setAttribute("type","password");
        document.getElementById("pass1").setAttribute("type","password");
        e = true;
    }
}