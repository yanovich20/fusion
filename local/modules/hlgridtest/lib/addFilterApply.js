BX.ready(function(){
    BX.addCustomEvent('BX.Main.Filter:apply', BX.delegate(function (command, params) { 
        if(command=="VISITORS_FILTER"){
            var reloadParams = { apply_filter: 'Y', clear_nav: 'Y' };
            var gridObject = BX.Main.gridManager.getById('VISITORS_GRID'); // Идентификатор грида

            if (gridObject.hasOwnProperty('instance')){
                    gridObject.instance.reloadTable('POST', reloadParams,null,"/local/modules/hlgridtest/lib/grid.php");
                }
            }
        }))
    }
);
