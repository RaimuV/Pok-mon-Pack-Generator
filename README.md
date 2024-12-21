# Pokémon Card Pack Generator

## Overview
This project allows users to generate random Pokémon card packs using the Pokémon TCG API. By pressing the "Generate a random pack" button, users can retrieve a set of random Pokémon cards, which are then displayed with images and details. The UI includes a loading screen that displays a spinning Poké Ball while the cards are being fetched.

## Features
- Fetch random Pokémon cards from the Pokémon TCG API.
- Display the Pokémon card images and details.
- A loading screen with a Mew animation while the cards are loading.
- Responsive design for better user experience across devices.

## Prerequisites
To run this project locally, you need a web server like **Apache** with PHP support.

- **PHP 7.0+**: The server needs to support PHP for executing the API requests and rendering the page.
- **Apache or any PHP-compatible web server**: This is required to serve the project files and handle the POST requests.
  
## Setup
1. Clone this repository to your local machine:
    ```bash
    git clone https://github.com/your-username/pokemon-card-pack-generator.git
    ```
2. Move the project folder into your web server's document root directory (e.g., `htdocs` for XAMPP or `www` for Apache).
3. Ensure that the **Apache** server is running.
4. Open the project in a web browser via `http://localhost/pokemon-card-pack-generator`.

## API Used
This project uses the [Pokémon TCG API](https://pokemontcg.io/) to retrieve the card data.

- API Documentation: [https://pokemontcg.io/docs](https://pokemontcg.io/docs)
  
The API is used to fetch random Pokémon cards and display their images, names, and other details.

## Card Styling
The card styling for displaying the Pokémon cards was inspired by the CodePen by [SimeyDotMe](https://codepen.io/simeydotme/pen/PrQKgo), with some adjustments made to integrate it with the API data.

- CodePen for card styling: [https://codepen.io/simeydotme/pen/PrQKgo](https://codepen.io/simeydotme/pen/PrQKgo)

## Screenshots
![image](https://github.com/user-attachments/assets/4f62b9fe-fdfe-4157-9ab7-98f8a89ded65)


## License
This project is open source and available under the MIT License.

## Acknowledgments
- Thanks to [Pokémon TCG API](https://pokemontcg.io/) for providing the API to fetch Pokémon card data.
- Card CSS inspiration from [SimeyDotMe's CodePen](https://codepen.io/simeydotme/pen/PrQKgo).
