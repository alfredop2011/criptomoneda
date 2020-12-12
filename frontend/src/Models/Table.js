class Table{
    
    constructor(id, options){
        options.language = {url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Spanish.json'};
        this.node =  $(id).DataTable(options);

        this.node.on('error.dt', function (e, settings, techNote, message) {
            console.log('An error has been reported by DataTables: ', message);
        });
    }
}

module.exports = Table;