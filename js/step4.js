function showElement(element, displayValue) {
  element.style.display = displayValue;
}

// Initialisation des éléments
const sectorActivitySelector = document.getElementById("sector_activity_selector");
const industrySelector = document.getElementById("industry_selector");
const buildingTypeInput = document.getElementById("building_type_input");
const commerceSectorSelector = document.getElementById("commerce_sector_selector");
const transportSectorSelector = document.getElementById("transport_sector_selector");

// Afficher initialement les éléments nécessaires
showElement(industrySelector, "block");
showElement(buildingTypeInput, "none");
showElement(commerceSectorSelector, "none");
showElement(transportSectorSelector, "none");

// Écouter les changements sur le secteur d'activité
sectorActivitySelector.addEventListener("change", (event) => {
  const selectedOption = event.target.value;

  // Masquer tous les sélecteurs initialement
  showElement(industrySelector, "none");
  showElement(buildingTypeInput, "none");
  showElement(commerceSectorSelector, "none");
  showElement(transportSectorSelector, "none");

  // Afficher le sélecteur pertinent en fonction de l'option sélectionnée
  switch (selectedOption) {
    case "Industrie":
      showElement(industrySelector, "block");
      break;
    case "Construction":
      showElement(buildingTypeInput, "block");
      break;
    case "Commerce":
      showElement(commerceSectorSelector, "block");
      break;
    case "Service":
      showElement(transportSectorSelector, "block");
      break;
    default:
      // Ne rien faire pour les autres options
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
