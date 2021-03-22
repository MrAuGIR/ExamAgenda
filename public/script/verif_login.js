let valid_login = () =>{
    let error = false;

    let email = document.getElementById('inputEmail');
    let password = document.getElementById('inputPassword');

    //regex pour verifier l'email
    let regexCourriel = /.+@.+\..+/;
    //on verifie l'email '
    if(email.value === '' ||  !regexCourriel.test(email.value)){
        error = true;
        styleLabel('labelEmail',true);
    }else{
        styleLabel('labelEmail');
    }

    //on verifie la confirmation du mot de passe
    if(password.value === ""){
        error = true;
        styleLabel('labelPassword', true);
    }else{
        styleLabel('labelPassword');
    }
        
    if(error){
        return false;
    }else{
        return true;
    }
}

// met en forme les labels en fonction de la saisie

function styleLabel(id,error=false){
    if(error){
        document.getElementById(id).style.color='red';
        document.getElementById(id).style.fontWeight='bold';
    }else{
        document.getElementById(id).style.color='black';
        document.getElementById(id).style.fontWeight='normal';
    }
}

//listener sur le click sur la balise span

let eye = document.getElementById('eye');
eye.addEventListener('click',function (e){
    visible('password');
})

//rendre visible le mot de passe
function visible(id){
    let password = document.getElementById(id);
    if(password.getAttribute('type') == "password"){
        password.setAttribute("type", "text");
    }else{
        password.setAttribute("type", "password");
    }
}