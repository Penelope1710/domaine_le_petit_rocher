import validationIconPath from '../images/form/icons8-coche.svg';

  const registrationForm = document.getElementById('registration-form');
  if (registrationForm) {
      console.log("DOM chargé et formulaire détecté.");
      
  
const inputsValidity = {
    lastName: false,
    firstName: false,
    phoneNumber: false,
    email: false,
    address: false,
    zipCode: false,
    city: false,
    birthDate: false,
    horseName: false,
    password: false, 
    passwordConfirmation: false
  }

  
  
  //fonction affichage icone/texte erreur/bordure
  //Destructuring: je passe directement les propriétés de l'objet en paramètres
 
  const validationIcons = document.querySelectorAll(".icone-verif");
  const validationTexts = document.querySelectorAll(".error-msg");
  const validationIconsHorse = document.querySelectorAll(".icone-verif-horse");
  const validationTextsHorse = document.querySelectorAll(".error-msg-horse");
  
  
  function showValidation({index, validation, input}) {
      if(validation){
        validationIcons[index].style.display = "inline";
        validationIcons[index].src = validationIconPath;
        validationTexts[index].style.display = "none";
        input.classList.remove('red-border');
        input.classList.add('green-border');
      } 
      else {
          validationIcons[index].style.display = "none";
          validationTexts[index].style.display = "block";
          input.classList.remove('green-border');
          input.classList.add('red-border');
      }
    }
   function showValidationHorse({index, validation, input}) {
      if(validation){
        validationIconsHorse[index].style.display = "inline";
        validationIconsHorse[index].src = validationIconPath;
        validationTextsHorse[index].style.display = "none";
        input.classList.remove('red-border');
        input.classList.add('green-border');
      } 
      else {
          validationIconsHorse[index].style.display = "none";
          validationTextsHorse[index].style.display = "block";
          input.classList.remove('green-border');
          input.classList.add('red-border');
      }
    } 

    
    //NOM 
    const lastNameInput = document.querySelector("#lastName-group input");
    
    function lastNameCheck(){
      if(lastNameInput.value.length >= 2) {
        showValidation({index: 0, validation: true, input: lastNameInput})
        inputsValidity.lastName = true;
      }
      else {
        showValidation({index: 0, validation: false, input: lastNameInput})
        inputsValidity.lastName = false;
      }
    }
  
    //PRENOM
    const firstNameInput = document.querySelector("#firstName-group input");
    
    function firstNameCheck(){
      if(firstNameInput.value.length >= 2) {
        showValidation({index: 1, validation: true, input: firstNameInput})
        inputsValidity.firstName = true;
      }
      else {
        showValidation({index: 1, validation: false, input: firstNameInput})
        inputsValidity.firstName = false;
      }
    }
  
    //EMAIL
    const mailInput = document.querySelector("#email-group input");
    
    const regexEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    
    function mailCheck(){
      if(regexEmail.test(mailInput.value)){
        showValidation({index: 2, validation: true, input: mailInput})
        inputsValidity.email = true;
      } else {
        showValidation({index: 2, validation: false, input: mailInput})
        inputsValidity.email = false;
      }
    }
  
    //PORTABLE
    const phoneInput = document.querySelector("#phoneNumber-group input")
  
    const regexPhoneNumber = /^[0-9]+$/;
  
    function phoneCheck() {
      if(regexPhoneNumber.test(phoneInput.value)) {
        showValidation({index: 3, validation: true, input: phoneInput})
        inputsValidity.phoneNumber = true;
      } else {
        showValidation({index: 3, validation: false, input: phoneInput})
        inputsValidity.phoneNumber = false;
      }
    }
  
    //ADRESSE
    const addressInput = document.querySelector("#address-group input");
    
    function addressCheck(){
      if(addressInput.value.length >= 2) {
        showValidation({index: 4, validation: true, input: addressInput})
        inputsValidity.address = true;
      }
      else {
        showValidation({index: 4, validation: false, input: addressInput})
        inputsValidity.address = false;
      }
    }
  
    // CODE POSTAL
    const zipCodeInput = document.querySelector("#zipCode-group input");
  
    const zipCodeRegex = /^[0-9]+$/;
    
    function zipCodeCheck(){
      if(zipCodeRegex.test(zipCodeInput.value)) {
        showValidation({index: 5, validation: true, input: zipCodeInput})
        inputsValidity.zipCode = true;
      }
      else {
        showValidation({index: 5, validation: false, input: zipCodeInput})
        inputsValidity.zipCode = false;
      }
    }
  
    // VILLE
    const cityInput = document.querySelector("#city-group input");
    
    function cityCheck(){
      if(cityInput.value.length >= 2) {
        showValidation({index: 6, validation: true, input: cityInput})
        inputsValidity.city = true;
      }
      else {
        showValidation({index: 6, validation: false, input: cityInput})
        inputsValidity.city = false;
      }
    }
  
  // DATE DE NAISSANCE
  const birthDateInput = document.querySelector("#birthDate-group input");
  
  function birthDateCheck() {
      const birthDateValue = birthDateInput.value; 
      const birthDate = new Date(birthDateValue); // Transforme en objet Date
  
      // Vérifie si le champ n'est pas vide et si la date est valide
      if (birthDateValue && !isNaN(birthDate.getTime())) {
          showValidation({ index: 7, validation: true, input: birthDateInput });
          inputsValidity.birthDate = true;
      } else {
          showValidation({ index: 7, validation: false, input: birthDateInput });
          inputsValidity.birthDate = false;
      }
  }

  
  // NOM DU CHEVAL
  const horseNameInput = document.querySelector("#horseName-group input");

  function horseNameCheck() {
    if(!horseNameInput) {
      inputsValidity.horseName = true;
      return;
    }
    if(horseNameInput.value.length >= 2) {
      showValidationHorse({index: 0, validation: true, input: horseNameInput});
      inputsValidity.horseName = true;
  } else {
      showValidationHorse({index: 0, validation: false, input: horseNameInput})
      inputsValidity.horseName = false;
  }
}
 
  // MOT DE PASSE
  const passwordInput = document.querySelector("#password-group input");
  const passwordAlert = document.querySelectorAll('.password-alert');
  
  //en vert si condition valide
  const inpuPwdValid = (index) => {
      passwordAlert[index].classList.remove('error');
      passwordAlert[index].classList.add('valid');
  }
  //reste en rouge si condition invalide
  const inpuPwdInvalid = (index) => {
      passwordAlert[index].classList.remove('valid');
      passwordAlert[index].classList.add('error');
  }
  
  const pwdCheck = function() {
      //récupération du mot de passe
      let password = passwordInput.value;
      //conditions de validation
      const lengthIsValid = password.length >=8;
      const upperIsValid = /[A-Z]/.test(password);
      const lowerIsValid = /[a-z]/.test(password);
      const numberIsValid = /\d/.test(password);
      const specialIsValid = /[^a-zA-Z0-9\s]/.test(password);
  
      //les conditions en ternaire
      lengthIsValid ? inpuPwdValid(0) : inpuPwdInvalid(0);
      upperIsValid ? inpuPwdValid(1) : inpuPwdInvalid(1);
      lowerIsValid ? inpuPwdValid(2) :  inpuPwdInvalid(2);
      numberIsValid ? inpuPwdValid(3) :  inpuPwdInvalid(3);
      specialIsValid ? inpuPwdValid(4) : inpuPwdInvalid(4);
         
  
      //validité du mot de passe entier
      if(lengthIsValid && upperIsValid && lowerIsValid && numberIsValid && specialIsValid) {
        showValidation({index: 8, validation: true, input: passwordInput});
        inputsValidity.password = true;
      } else {
        showValidation({index: 8, validation: false, input: passwordInput});
        inputsValidity.password = false;
      }
  };
  
  //CONFIRMATION DU MOT DE PASSE
  const confirmPwdInput = document.querySelector("#confirmPassword-group input");
  
  function confirmPwdCheck(){
  // je récupère la valeur du mot de passe principal
    let passwordValue = passwordInput.value;
    //je récupère la valeur de la confirmation du mot de passe
    let confirmedValue = confirmPwdInput.value;
  
    if(!confirmedValue && !passwordValue) {
      validationIcons[3].style.display = "none";
    }
    else if(confirmedValue !== passwordValue) {
      showValidation({index: 9, validation: false, input: confirmPwdInput})
      inputsValidity.passwordConfirmation = false;
    }
    else {
      showValidation({index: 9, validation: true, input: confirmPwdInput})
      inputsValidity.passwordConfirmation = true;
    }
  } 
  
  
  //CONTROLE DES CHAMPS
  //champ Nom
  lastNameInput.addEventListener("blur", lastNameCheck);
  lastNameInput.addEventListener("input", lastNameCheck);
  
  //champ Prenom
  firstNameInput.addEventListener("blur", firstNameCheck);
  firstNameInput.addEventListener("input", firstNameCheck);
  
  //champ email
  mailInput.addEventListener("blur", mailCheck);
  mailInput.addEventListener("input", mailCheck);
  
  //champ telephone
  phoneInput.addEventListener("blur", phoneCheck);
  phoneInput.addEventListener("input", phoneCheck);
  
  //champ adresse
  addressInput.addEventListener("blur", addressCheck);
  addressInput.addEventListener("input", addressCheck);
  
  //champ code postal
  zipCodeInput.addEventListener("blur", zipCodeCheck);
  zipCodeInput.addEventListener("input", zipCodeCheck);
  
  
  //champ ville
  cityInput.addEventListener("blur", cityCheck);
  cityInput.addEventListener("input", cityCheck);
  
  //champ date de naissance
  birthDateInput.addEventListener("blur", birthDateCheck);
  birthDateInput.addEventListener("input", birthDateCheck);
  
  //champ nom cheval
  if(horseNameInput){
    horseNameInput.addEventListener("blur", horseNameCheck);
    horseNameInput.addEventListener("input", horseNameCheck);
  } else {
    console.warn("Le champ 'Nom du cheval' est introuvable. Validation ignorée.");
    inputsValidity.horseName = true; 
  }
  
  //champ mot de passe
  passwordInput.addEventListener("blur", pwdCheck);
  passwordInput.addEventListener("input", pwdCheck);
  
  //champ confirmation du mot de passe
  confirmPwdInput.addEventListener("blur", confirmPwdCheck)
  confirmPwdInput.addEventListener("input", confirmPwdCheck)
  
  
  //VALIDATION DU FORMULAIRE
  const form = document.querySelector("form");
  const submitButton = document.getElementById("submit-button");
 
    form.addEventListener("input", allInputsCkeck);
    function allInputsCkeck() {
    if(inputsValidity.lastName && inputsValidity.firstName && inputsValidity.email && inputsValidity.phoneNumber && inputsValidity.address && inputsValidity.zipCode && inputsValidity.city && inputsValidity.birthDate && inputsValidity.horseName && inputsValidity.password && inputsValidity.passwordConfirmation) {
      submitButton.disabled = false;
      submitButton.classList.remove("disabled");
    } else {
      submitButton.disabled = true;
      submitButton.classList.add("disabled");
    }
  }
  
  form.addEventListener("submit", handleForm); 
  form.addEventListener("blur", handleForm); 
  
  function handleForm(e) {
    if(lastNameCheck && firstNameCheck && mailCheck && phoneCheck && addressCheck && zipCodeCheck && cityCheck && birthDateCheck && horseNameCheck && pwdCheck && confirmPwdCheck) {
      alert("Vos données ont été envoyées avec succès")
      } else {
      e.preventDefault()
    }
  }
} else {
  console.error("Aucun formulaire trouvé avec l'ID 'registration-form'.");
}

  
  
  
  
  
     
  
  