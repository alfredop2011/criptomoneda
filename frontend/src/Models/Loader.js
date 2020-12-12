class Loader {
    constructor(node){
        this.node = node;
        this.tab = this.node.parents(".tab-pane")
    }

    getTab(){
        return this.tab;
    }

    /**
     * Elimina el contenido de la página y muestra el loader
     */
    show() {
        this.tab.find(".tab-info").addClass("d-none");
        this.showLoader();
    }

    /**
     * Elimina el loader de la vista y muestra el contenidos de la página
     */
    remove(){
        this.node.addClass("d-none");
        this.showTabInfo();
    }

    showLoader(){
        this.node.removeClass("d-none");
        this.tab.find(".progress").removeClass("d-none");
    }

    showTabInfo() {
        this.tab.find(".tab-info").removeClass("d-none");
    }
}

module.exports = Loader;