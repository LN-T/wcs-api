//Thanks for all the likes! Be sure to check out the new pen with geolocation based results!
//https://codepen.io/azureknight/pen/VYEBGZ
var $food = ['California maki', 'Butter chicken', 'Bahn mi', 'Bobun'];

var $fastRandom = Math.floor($food.length * Math.random());

var $randomFast = $food[$fastRandom];

var $drink = ['Aperol Spritz', 'Pisco Sour', 'Mai tai', 'Tequila Sunrise'];

var $restRandom = Math.floor($drink.length * Math.random());

var $randomRest = $drink[$restRandom];

var $movie = ['Dernier train pour Busan', 'The Amityville Horror', 'Dorothy', 'The Thing', 'Sharknado' ];

var $movieRandom = Math.floor($movie.length * Math.random());

var $randomDest = $movie[$movieRandom];

function reRoll(){

    var food = $food[Math.floor($food.length * Math.random())];
    var drink = $drink[Math.floor($drink.length * Math.random())];
    var movie = $movie[Math.floor($movie.length * Math.random())];

    $('#food').text(food);
    $('#drink').text(drink);
    $('#movie').text(movie);
    $('#food-url').attr("href", 'food/recipe/' + food);
    $('#drink-url').attr("href", 'food/recipe/' + drink);
    $('#movie-url').attr("href", 'https://www.netflix.com/search?q=' + movie);
}

$(document).ready(function(){
    $('#food').text($randomFast);
    $('#drink').text($randomRest);
    $('#movie').text($randomDest);
    $('#food-url').attr("href", 'food/recipe/' + $randomFast);
    $('#drink-url').attr("href", 'drink/recipe/' + $randomRest);
    $('#movie-url').attr("href", 'https://www.netflix.com/search?q=' + $randomDest);

});