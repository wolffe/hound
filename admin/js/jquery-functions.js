function createslug(){
    var valore=document.form1.title.value;
    valore=replaceAll(valore,"--","-");
    valore=replaceAll(valore,"- ", "");
    valore=replaceAll(valore,"/", "");
    valore=replaceAll(valore,"\\", "");
    valore=replaceAll(valore,"'", "");
    valore=replaceAll(valore,",", "");
    valore=replaceAll(valore,";", "");
    valore=replaceAll(valore,":", "");
    valore=replaceAll(valore,".", "-");
    valore=replaceAll(valore,"?", "");
    valore=replaceAll(valore,"=", "-");
    valore=replaceAll(valore,"+", "");
    valore=replaceAll(valore,"$", "");
    valore=replaceAll(valore,"&", "");
    valore=replaceAll(valore,"!", "");
    valore=replaceAll(valore,">>", "-");
    valore=replaceAll(valore,">", "-");
    valore=replaceAll(valore,"<<", "-");
    valore=replaceAll(valore,"<", "-");
    valore=replaceAll(valore,"*", "");
    valore=replaceAll(valore,")", "");
    valore=replaceAll(valore,"(", "");
    valore=replaceAll(valore,"[", "");
    valore=replaceAll(valore,"]", "");
    valore=replaceAll(valore,"^", "");
    valore=replaceAll(valore,"%", "");
    valore=replaceAll(valore,"ª", "-");
    valore=replaceAll(valore,"|", "");
    valore=replaceAll(valore,"#", "");
    valore=replaceAll(valore,"@", "");
    valore=replaceAll(valore," ", "-");
    valore=replaceAll(valore,"`", "");
    valore=replaceAll(valore,"î", "");
    valore=replaceAll(valore,"ì", "");
    valore=replaceAll(valore,"\"", "");
    valore=replaceAll(valore,"_", "-");
    valore=valore.toLowerCase();
    document.form1.slug.value=valore;
}
function replaceAll( str, from, to ) {
    var idx = str.indexOf( from );
    while ( idx > -1 ) {
        str = str.replace( from, to );
        idx = str.indexOf( from );
    }
    return str;
}

function sortTable(table, order) {
    var asc = order === 'asc',
        tbody = table.find('tbody');

    tbody.find('tr').sort(function(a, b) {
        if (asc) {
            return $('td:first', a).text().localeCompare($('td:first', b).text());
        } else {
            return $('td:first', b).text().localeCompare($('td:first', a).text());
        }
    }).appendTo(tbody);
}

$(document).ready(function() {
    sortTable($('.hd-sortable'), 'asc');
});
