const Table = require('../Models/Table')

class TableController{
    /**
     * Añade una fila a la tabla
     * 
     * @param {Array} values 
     */
    addRow(table, values){
        table.node.row.add(values).draw(true);
    }

    /**
     * Elimina una fila de la tabla
     * 
     * @param {string} id   Identificador de la fila 
     */
    deleteRow(table, id) {
        table.node.row(id).delete();
    }
}

module.exports = TableController;