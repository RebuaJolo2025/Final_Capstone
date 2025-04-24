const products = [
    {
        id: 1,
        name: "Wireless Bluetooth Headphones",
        price: "$79.99",
        image: "https://via.placeholder.com/300x200?text=Headphones",
        influencer: "TechGuru",
        description: "High-quality wireless headphones with noise cancellation and 20 hours of battery life. Perfect for music lovers and professionals.",
    },
    {
        id: 2,
        name: "Smart Fitness Watch",
        price: "$149.99",
        image: "https://via.placeholder.com/300x200?text=Fitness+Watch",
        influencer: "HealthExpert",
        description: "Track your steps, heart rate, and sleep cycles with this sleek and stylish fitness watch. Compatible with iOS and Android.",
    },
    {
        id: 3,
        name: "Portable Blender",
        price: "$49.99",
        image: "https://via.placeholder.com/300x200?text=Blender",
        influencer: "SmoothieKing",
        description: "Make smoothies on the go! This portable blender is perfect for fitness enthusiasts and busy individuals.",
    },
    {
        id: 4,
        name: "Wireless Charging Pad",
        price: "$29.99",
        image: "https://via.placeholder.com/300x200?text=Charging+Pad",
        influencer: "GadgetPro",
        description: "Keep your devices powered up with this sleek, fast-charging wireless pad. Compatible with all Qi-enabled devices.",
    },
    {
        id: 5,
        name: "Ergonomic Office Chair",
        price: "$199.99",
        image: "https://via.placeholder.com/300x200?text=Office+Chair",
        influencer: "WorkFromHomeGuru",
        description: "A comfortable and ergonomic office chair that provides support for long working hours, with adjustable height and lumbar support.",
    },
    {
        id: 6,
        name: "Smartphone Camera Lens Kit",
        price: "$39.99",
        image: "https://via.placeholder.com/300x200?text=Camera+Lens",
        influencer: "PhotographyPro",
        description: "Take your smartphone photography to the next level with this lens kit. Includes wide-angle, macro, and fisheye lenses.",
    }
];

// Function to display products on the homepage
function displayProducts() {
    const productList = document.getElementById("product-list");

    products.forEach(product => {
        const productElement = document.createElement("div");
        productElement.classList.add("product-item");
        productElement.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.price}</p>
            <a href="product-detail.html?id=${product.id}" class="buy-now">View Details</a>
        `;
        productList.appendChild(productElement);
    });
}

// Function to display product details on the product page
function displayProductDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get("id");

    const product = products.find(p => p.id == productId);
    if (product) {
        document.getElementById("product-title").innerText = product.name;
        document.getElementById("product-description").innerText = product.description;
        document.getElementById("product-price").innerText = product.price;
        document.getElementById("influencer-name").innerText = `Endorsed by: ${product.influencer}`;
    }
}

// Event listener for search functionality
document.getElementById("searchBar").addEventListener("input", function(event) {
    const query = event.target.value.toLowerCase();
    const filteredProducts = products.filter(product =>
        product.name.toLowerCase().includes(query)
    );

    const productList = document.getElementById("product-list");
    productList.innerHTML = ''; // Clear previous products

    filteredProducts.forEach(product => {
        const productElement = document.createElement("div");
        productElement.classList.add("product-item");
        productElement.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.price}</p>
            <a href="product-detail.html?id=${product.id}" class="buy-now">View Details</a>
        `;
        productList.appendChild(productElement);
    });
});

// Run on page load
if (document.getElementById("product-list")) {
    displayProducts();
}

if (document.querySelector(".product-detail")) {
    displayProductDetails();
}
