import "@babel/polyfill";
import "./sass/style.scss";
import $ from 'jquery';
import 'bootstrap';
window.$ = $;
import dt from 'datatables.net';



const Table = require('./Models/Table');
const TableController = require('./Controllers/TableController');
const Loader = require('./Models/Loader');
const CoinController = require('./Controllers/CoinController');

var tc = new TableController();
var cc = new CoinController();

let loaders = {};

document.addEventListener("DOMContentLoaded", () => {
    $.each($(".tab-loader-wrapper"), function () {
        if (typeof $(this) != undefined) {
            let loader = new Loader($(this));
            loaders[loader.getTab().attr("id")] = loader;
        }
    });

    const favoritesTable = new Table('#favourites-table', {
        "paging": true
    });

    const currenciesTable = new Table('#currencies-table', {
        "paging": true,
         columnDefs: [{
            targets: [2,3,4,5],
            className: 'dt-body-right'
        },{
           targets: [1],
           className: 'dt-body-large'
        }]
    });

    init(currenciesTable);
    loadCoins();
    

    $("#check-coins").on('click', function(){
        loaders.tab1.show();
        $("#options-tab-button").removeClass("active");
        $("#tab3").removeClass(["active","show"]);
        $("#currencies-tab-button").addClass("active");
        $("#tab1").addClass(["active","show"]);
    });
    
});

/**
 * Realiza la carga de las monedas desde la base de datos
 * en la tabla de la vista
 * 
 * @param {Table} table     Tabla donde se muestran las monedas
 */
function init(table) {
    console.log("Cargando monedas");
    cc.load().then((data) => {
        let coins = data;
        for (let id in coins) {

            console.log(`Cargando ${coins[id].name}`);

            let image = '';

            if(typeof coins[id].symbol == 'undefined'){
                coins[id].symbol = "";
            }
            if (typeof coins[id].name == 'undefined') {
                coins[id].name = "";
            }else{
                coins[id].name = `<strong>${coins[id].name}</strong>`;
            }
            if (typeof coins[id].price == 'undefined') {
                coins[id].price = 0;
            } else if (coins[id].price.length == 0) {
                coins[id].price = "---";
            } else {
                coins[id].price = `<strong>${coins[id].price}€</strong>`;
            }
            if (typeof coins[id].high24 == 'undefined') {
                coins[id].high24 = 0;
            } else if (coins[id].high24.length == 0) {
                coins[id].high24 = "---";
            }else{
                coins[id].high24+="€";
            }
            if (typeof coins[id].low24 == 'undefined') {
                coins[id].low24 = 0;
            } else if (coins[id].low24.length == 0) {
                coins[id].low24 = "---";
            } else {
                coins[id].low24 += "€";
            }
            if (typeof coins[id].change24 == 'undefined') {
                coins[id].change24 = 0;
            } else if (coins[id].change24.length == 0) {
                coins[id].change24 = "---";
            } else if (coins[id].change24 < 0) {
                coins[id].change24 = `<strong><span class="text-danger">${coins[id].change24}%</span></strong>`;
            } else if (coins[id].change24 > 0){
                coins[id].change24 = `<strong><span class="text-success">${coins[id].change24}%</span></strong>`;
            }

            if (typeof coins[id].image != 'undefined'){
                image = `<img class="img-responsive" src="${coins[id].image}">`
            }
            
            tc.addRow(table, [image, coins[id].name + ` (${coins[id].symbol.toUpperCase()})`, coins[id].price, coins[id].high24, coins[id].low24, coins[id].change24]);
        }
    });
}

/**
 * Carga las monedas de la API en el atributo del controlador
 * que mantiene un seguimiento de las moendas disponibles
 */
function loadCoins() {
    cc.list().then((data) => {
        cc.setCoins(data);
    });
}

/**
 * Realiza la comprobación de las monedas controlando
 * el proceso por el que se añaden nuevas monedas a la
 * base de datos
 * 
 * @param {Loader} loader    Controlador de la animación de carga
 */
function checkCoins(loader) {
    let coins;
    
    cc.list().then((data) => {
        cc.setCoins(data);
        let checked = 0;

        setInterval(function () {
            coins = cc.loadCoins(5);
            console.log(coins);
            let start = cc.getLast()-5;
            for (let id in coins) {
                cc.check(start + parseInt(id));
                checked++;

                if(id == cc.getCoins().length){
                    loader.remove();
                }
            }
            setProgress(checked, cc.getCoins().length);
        }, 10*1000);

        cc.reset();
    });
}

/**
 * Controla la barra de progreso del proceso de comprobación
 * 
 * @param {Integer} checked   Monedas comprobadas
 * @param {Integer} total     Total de monedas a comprobar
 */
function setProgress(checked, total) {
    let progress = parseInt((checked / total) * 100);
    $("#coins-progress-checking").css("width", `${progress}%`);
    $("#coins-progress-checking").text(`${progress}%`);
}