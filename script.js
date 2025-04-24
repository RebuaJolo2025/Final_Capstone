// Wait for the DOM to load before running the script
document.addEventListener("DOMContentLoaded", function () {
    let searchBar = document.getElementById("searchBar");
    let products = document.querySelectorAll(".product-item");
    let noResults = createNoResultsMessage(); // Create "No Results" message

    // Attach event listener to search bar
    searchBar.addEventListener("keyup", function () {
        filterProducts(this.value.toLowerCase().trim());
    });

    /**
     * Function to create and insert the "No Results" message
     */
    function createNoResultsMessage() {
        let noResults = document.createElement("p");
        noResults.id = "noResults";
        noResults.textContent = "No products found.";
        noResults.style.display = "none"; // Hide it initially
        noResults.style.textAlign = "center";
        noResults.style.fontWeight = "bold";
        noResults.style.color = "red";
        document.querySelector(".product-container").appendChild(noResults);
        return noResults;
    }

    /**
     * Function to filter products based on search input
     * @param {string} query - User's search query
     */
    function filterProducts(query) {
        let hasResults = false;

        products.forEach(function (product) {
            let productName = product.getAttribute("data-name").toLowerCase();
            if (productName.includes(query)) {
                product.style.display = "block"; // Show matching product
                hasResults = true;
            } else {
                product.style.display = "none"; // Hide non-matching products
            }
        });

        // Show or hide "No Results" message
        noResults.style.display = hasResults ? "none" : "block";
    }
});
