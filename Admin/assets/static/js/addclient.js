var form_1 = document.querySelector(".form_1");
var form_2 = document.querySelector(".form_2");
var form_4 = document.querySelector(".form_4");
var form_5 = document.querySelector(".form_5");

var form_1_btns = document.querySelector(".form_1_btns");
var form_2_btns = document.querySelector(".form_2_btns");

var form_4_btns = document.querySelector(".form_4_btns");
var form_5_btns = document.querySelector(".form_5_btns");

var form_1_next_btn = document.querySelector(".form_1_btns .btn_next");
var form_2_back_btn = document.querySelector(".form_2_btns .btn_back");
var form_2_next_btn = document.querySelector(".form_2_btns .btn_next");
var form_4_back_btn = document.querySelector(".form_4_btns .btn_back");
var form_4_next_btn = document.querySelector(".form_4_btns .btn_next");
var form_5_back_btn = document.querySelector(".form_5_btns .btn_back");


var btn_done = document.querySelector(".btn_done");
var modal_wrapper = document.querySelector(".modal_wrapper");
var shadow = document.querySelector(".shadow");

var errormsg = document.querySelector(".error-msg");
var pass_error = document.querySelector(".pass_error");
var pass_lengh = document.querySelector(".pass_lengh");

form_1_next_btn.addEventListener("click", function () {
  var nom_user = document.getElementById("nom_user").value;
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  var cpassword = document.getElementById("cpassword").value;

  var passRegex = /^(?=.*[A-Z]).{8,}$/;

  if (
    nom_user === "" ||
    username === "" ||
    password === "" ||
    cpassword === ""
  ) {
    errormsg.style.display = "block";
    pass_error.style.display = "none"; // Assurez-vous de cacher le message d'erreur de mot de passe
  } else {
    errormsg.style.display = "none";

    // Vérifier si les mots de passe correspondent
    if (!passRegex.test(password)) {
      pass_lengh.style.display = "block";
    } else if (password !== cpassword) {
      pass_error.style.display = "block"; // Afficher le message d'erreur de mot de passe
    } else {
      pass_error.style.display = "none"; // Cacher le message d'erreur de mot de passe

      // Passer à la prochaine étape
      form_1.style.display = "none";
      form_2.style.display = "block";

      form_1_btns.style.display = "none";
      form_2_btns.style.display = "flex";

      form_2_progessbar.classList.add("active");
    }
  }
});

form_2_back_btn.addEventListener("click", function () {
  form_1.style.display = "block";
  form_2.style.display = "none";

  form_1_btns.style.display = "flex";
  form_2_btns.style.display = "none";


});

form_2_next_btn.addEventListener("click", function () {
  form_2.style.display = "none";
  form_4.style.display = "block";

  form_4_btns.style.display = "flex";
  form_2_btns.style.display = "none";


});

form_4_back_btn.addEventListener("click", function () {
  form_2.style.display = "block";
  form_4.style.display = "none";

  form_4_btns.style.display = "none";
  form_2_btns.style.display = "flex";

  form_4_progessbar.classList.remove("active");
});

form_4_next_btn.addEventListener("click", function () {
  form_4.style.display = "none";
  form_5.style.display = "block";

  form_4_btns.style.display = "none";
  form_5_btns.style.display = "flex";


});

form_5_back_btn.addEventListener("click", function () {
  form_5.style.display = "none";
  form_4.style.display = "block";

  form_5_btns.style.display = "none";
  form_4_btns.style.display = "flex";


});




/// partie selectionner de input

const userTypeSelect = document.getElementById("user_type");
const userSexeInput = document.getElementById("user_sexe_input");
const userAgeInput = document.getElementById("user_age_input");
const userStatusInput = document.getElementById("user_status_input");
const userCompSizeInput = document.getElementById("user_comp_size_input");
const userServInput = document.getElementById("user_serv_input");
const userOrgtyp1Input = document.getElementById("user_orgtyp1_input");
const userOrgtyp2Input = document.getElementById("user_orgtyp2_input");
const userComInput = document.getElementById("user_com_input");
const userMena1Input = document.getElementById("user_mena1_input");
const userMena2Input = document.getElementById("user_mena2_input");

function showInputFields(
  sexe,
  age,
  status,
  compSize,
  serv,
  orgtyp1,
  orgtyp2,
  com,
  mena1,
  mena2
) {
  userSexeInput.style.display = sexe;
  userAgeInput.style.display = age;
  userStatusInput.style.display = status;
  userCompSizeInput.style.display = compSize;
  userServInput.style.display = serv;
  userOrgtyp1Input.style.display = orgtyp1;
  userOrgtyp2Input.style.display = orgtyp2;
  userComInput.style.display = com;
  userMena1Input.style.display = mena1;
  userMena2Input.style.display = mena2;
}

showInputFields(
  "block",
  "block",
  "block",
  "none",
  "none",
  "none",
  "none",
  "none",
  "none",
  "none"
);

userTypeSelect.addEventListener("change", (event) => {
  const selectedOption = event.target.value;

  switch (selectedOption) {
    case "Personne physique":
      showInputFields(
        "block",
        "block",
        "block",
        "none",
        "none",
        "none",
        "none",
        "none",
        "none",
        "none"
      );
      break;
    case "Personne morale":
      showInputFields(
        "none",
        "none",
        "none",
        "block",
        "none",
        "none",
        "none",
        "none",
        "none",
        "none"
      );
      break;
    case "Service public":
      showInputFields(
        "none",
        "none",
        "none",
        "none",
        "block",
        "none",
        "none",
        "none",
        "none",
        "none"
      );
      break;
    case "Organisme":
      showInputFields(
        "none",
        "none",
        "none",
        "none",
        "none",
        "block",
        "block",
        "none",
        "none",
        "none"
      );
      break;
    case "Communauté":
      showInputFields(
        "none",
        "none",
        "none",
        "none",
        "none",
        "none",
        "none",
        "block",
        "none",
        "none"
      );
      break;
    case "Menage":
      showInputFields(
        "none",
        "none",
        "none",
        "none",
        "none",
        "none",
        "none",
        "none",
        "block",
        "block"
      );
      break;
    default:
    // Gérer d'autres options au besoin
  }
});

const accountType = document.getElementById("account_type");
const actorType = document.getElementById("actor_type");
const actorTypeInput = document.getElementById("actor_type_input"); // Notez la modification ici
const actorType2 = document.getElementById("actor_type_input2");
const actorBudginput = document.getElementById("actor_budg_input");
const agentAccount = document.getElementById("agent_account_input");
const detailantOptions = document.getElementById("detailant_options");

function showInputFields2(actor, actor2, actorBudg, agent) {
  actorTypeInput.style.display = actor;
  actorType2.style.display = actor2;
  actorBudginput.style.display = actorBudg;
  agentAccount.style.display = agent;
}

showInputFields2("none", "none", "none", "none", "none");

accountType.addEventListener("change", (event) => {
  const selectedOption = event.target.value;

  // Afficher ou masquer les éléments en fonction de la valeur sélectionnée
  switch (selectedOption) {
    case "Demandeur":
      showInputFields2("none", "none", "none", "none");
      break;
    case "Fournisseur":
      showInputFields2("block", "none", "none", "none");
      break;
    case "Livreur":
      showInputFields2("none", "block", "none", "none");
      break;
    case "investisseur":
      showInputFields2("none", "none", "block", "none");
      break;
    case "Agent":
      showInputFields2("none", "none", "none", "block");
      break;
    default:
  }

  // Gérer l'affichage de detailantOptions en fonction de actor_type
});

const sectorActivitySelector = document.getElementById(
  "sector_activity_selector"
);
const industrySelector = document.getElementById("industry_selector");
const buildingTypeInput = document.getElementById("building_type_input");
const commerceSectorSelector = document.getElementById(
  "commerce_sector_selector"
);
const transportSectorSelector = document.getElementById(
  "transport_sector_selector"
);

function showInputFields(selector, displayValue) {
  selector.style.display = displayValue;
}

showInputFields(industrySelector, "block");
showInputFields(buildingTypeInput, "none");
showInputFields(commerceSectorSelector, "none");
showInputFields(transportSectorSelector, "none");

sectorActivitySelector.addEventListener("change", (event) => {
  const selectedOption = event.target.value;

  // Hide all selectors initially
  showInputFields(industrySelector, "none");
  showInputFields(buildingTypeInput, "none");
  showInputFields(commerceSectorSelector, "none");
  showInputFields(transportSectorSelector, "none");

  // Show the relevant selector based on the selected option
  switch (selectedOption) {
    case "Industrie":
      showInputFields(industrySelector, "block");
      break;
    case "Construction":
      showInputFields(buildingTypeInput, "block");
      break;
    case "Commerce":
      showInputFields(commerceSectorSelector, "block");
      break;
    case "Service":
      showInputFields(transportSectorSelector, "block");
      break;
    default:

    // Do nothing for other options
  }
});

fetch("https://restcountries.com/v3.1/all")
  .then((response) => response.json())
  .then((data) => {
    const countryDropdown = document.getElementById("country");

    // Ajouter la Côte d'Ivoire en tant que première option
    const optionIvoryCoast = document.createElement("option");
    optionIvoryCoast.value = "Cote d'ivoire";
    optionIvoryCoast.textContent = "Côte d'Ivoire";
    countryDropdown.appendChild(optionIvoryCoast);

    // Ajouter les autres pays
    data.forEach((country, index) => {
      const option = document.createElement("option");
      option.value = "option" + (index + 2);
      option.textContent = country.name.common;
      countryDropdown.appendChild(option);
    });
  })
  .catch((error) =>
    console.error("Erreur lors de la récupération des pays", error)
  );

function populateCountryDropdown() {
  const countryDropdown = document.getElementById("country_code");
  fetch("https://restcountries.com/v2/all")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((country) => {
        if (
          country.hasOwnProperty("callingCodes") &&
          country.callingCodes.length > 0
        ) {
          const countryCode = country.callingCodes[0];
          const countryName = country.name;
          const option = document.createElement("option");
          option.value = countryCode;
          option.textContent = `${countryName} (+${countryCode})`;
          countryDropdown.appendChild(option);
        }
      });
    })
    .catch((error) =>
      console.error("Erreur lors de la récupération des pays", error)
    );
}

populateCountryDropdown();
