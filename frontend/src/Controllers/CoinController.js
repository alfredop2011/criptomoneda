const Coin = require('../Models/Coin');;

class CoinController {

    constructor() {
        this.coinGeckoApi = "https://api.coingecko.com/api/v3";
        this.last = 0;
    }

    load(){
        return fetch(`backend/index.php/load_coins`)
            .then((response) => {
                return response.json().then((data) => {
                    return data;
                }).catch((err) => {
                    console.log(err);
                })
            });
    }
    /**
     * Devuelve el listado de las criptomonedas disponibles en la API
     * 
     * @return {Array}
     */
    list() {
        return fetch(`${this.coinGeckoApi}/coins/list`)
            .then((response) => {
                return response.json().then((data) => {
                    return data;
                }).catch((err) => {
                    console.log(err);
                })
            });
    }

    check(id){
        let currency;

        if (typeof this.coins[id] !== 'undefined'){
            currency = this.coins[id].id;
            console.log(`+ ${currency} (${id})`);

        fetch(`backend/index.php/get_coin?currency=${currency}`).then(res => res.json())
            .then(data => {
                if(data.length == 0){
                    console.log("Solicitando " + currency);
                    fetch(`${this.coinGeckoApi}/coins/${currency}`)
                        .then(res => res.json().then(data => {
                            console.log(data);

                            coin = {
                                identifier : data.id,
                                symbol : data.symbol,
                                name : data.name,
                                link : data.links.homepage[0],
                                image: data.image.thumb,
                                price: data.market_data.current_price.eur,
                                description: data.description.es,
                                high24 : data.market_data.high_24h.eur,
                                low24: data.market_data.low_24h.eur,
                                change24: data.market_data.price_change_percentage_24h,
                            }

                            
                            fetch("backend/index.php/add_new_currency", {
                                    method: "POST",
                                    mode: "same-origin",
                                    credentials: "same-origin",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        coin
                                    })
                                }).then(res => res.json())
                                .then(data => {
                                    console.log(data);
                                });
                        }));
                    
                }else{
                    console.log("Ya alamacenada");
                }
            });
        }else{
            console.log(`- ${currency} (${id})`);
        }
    }

    /**
     * Carga típicamente las monedas obtenidas a través de la API
     * como un array de objetos
     * 
     * @param {Array} data  Monedas cargadas en el frontend
     */
    setCoins(data) {
        this.coins = data;
    }

    /**
     * Devuelve las monedas almacenadas en el cliente
     * 
     * @return Array
     */
    getCoins() {
        return this.coins;
    }

    /**
     * Recibe un número que especifica una porcion del array de monedas
     * que deben ser devueltas comenzando por la útlima que fue obtenida.
     * Por ejemplo, si la última moneda devuelta tenia el indice 27 (indicado)
     * por el indice last del controlador y se recibe un 3, la función accederá
     * al array coins y devolvera las siguientes 3 monedas, es decir aquellas
     * con los índicaes 28, 29 y 30. Po último actualizará el índice last a 30.
     * 
     * @param {Integer} number  Número de monedas que serán devueltas
     * 
     * @return {Array} 
     */
    loadCoins(number) {
        let currencies = this.coins.slice(this.last, this.last + number);
        this.last += number;

        return currencies;
    }

    /**
     * Recibe un número que es usado para obtener la información de la moneda cuyo
     * indice en el array coins coincide con dicho número. Obtiene la información
     * de la moneda desde la API
     * 
     * @param {Integer} number  Indice de la moneda en el array coins
     * @param {Boolean} card    Indica si se debe mostrar la tarjeta en la vista
     */
    getCoin(number) {
        fetch(`${this.coinGeckoApi}/coins/${this.coins[this.last].id}`)
            .then(res => res.json().then(data => {
                //console.log(data);
                //console.log(data.image.thumb);
            }));
    }

    getLast(){
        return this.last;
    }

    reset(){
        this.last = 0;
    }

    /**
     * Recibe un array de identificadores de monedas y devuelve una 
     * cadena para poder realizar la petición a la API
     * 
     * @param {Array} coins     Identificadores de la criptomoneda
     * 
     * @returns {String}
     */
    buildUrlTail(coins) {
        let i;
        let tail = "";

        for (let i = 0; i < coins.length; i++) {
            if (i == (coins.length - 1)) {
                tail += coins[i].id;
            } else {
                tail += coins[i].id + "%2C";
            }
        }

        return tail;
    }
}

module.exports = CoinController;