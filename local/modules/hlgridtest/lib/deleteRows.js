async function deleteSelectedRows(rows){
    let selectedRowsIds=[];
    if(!rows)
    {
        let selectedRows = document.querySelectorAll(".main-grid-row-checkbox:checked");
        selectedRows.forEach(function(row){
            let tr = row.closest("tr");
            let id = tr.dataset.id;
            selectedRowsIds.push(id);
        })
    }
    else
        selectedRowsIds = rows;
    let data = {};
    data.rows = selectedRowsIds;
    BX.ajax.post("/local/modules/hlgridtest/lib/delete.php",data,
        function(result){
            if(result.status=="success")
                alert(result.message);
            var reloadParams = { apply_filter: 'Y', clear_nav: 'Y' };
            var gridObject = BX.Main.gridManager.getById('VISITORS_GRID'); // Идентификатор грида

            if (gridObject.hasOwnProperty('instance')){
                    gridObject.instance.reloadTable('POST', reloadParams,null,"/local/modules/hlgridtest/lib/grid.php");
                }
        });
}