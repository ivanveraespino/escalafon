function customizeExcel(xlsx) {
    var sheet = xlsx.xl.worksheets['sheet1.xml'];
    var styles = xlsx.xl['styles.xml'];

    // Define fill color styles
    var fillColorHeader = '<fill><patternFill patternType="solid"><fgColor rgb="E0E0E0"/><bgColor indexed="64"/></patternFill></fill>';
    var fillColorOdd = '<fill><patternFill patternType="solid"><fgColor rgb="E0E0E0"/><bgColor indexed="64"/></patternFill></fill>';
    var fillColorEven = '<fill><patternFill patternType="solid"><fgColor rgb="FFFFFF"/><bgColor indexed="64"/></patternFill></fill>';
    var borderColor = '<border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border>';

    $(styles).find('fills').append(fillColorHeader).append(fillColorOdd).append(fillColorEven);
    $(styles).find('borders').append(borderColor);

    var fillIndexHeader = $(styles).find('fills fill').length - 3;
    var fillIndexOdd = $(styles).find('fills fill').length - 2;
    var fillIndexEven = $(styles).find('fills fill').length - 1;
    var borderIndex = $(styles).find('borders border').length - 1;

    var cellXfsHeader = '<xf fillId="' + fillIndexHeader + '" borderId="' + borderIndex + '" applyFill="1" applyBorder="1" applyFont="1"><alignment horizontal="center"/><font><sz val="15"/><b/><u/></font></xf>';
    var cellXfsOdd = '<xf fillId="' + fillIndexOdd + '" borderId="' + borderIndex + '" applyFill="1" applyBorder="1"/>';
    var cellXfsEven = '<xf fillId="' + fillIndexEven + '" borderId="' + borderIndex + '" applyFill="1" applyBorder="1"/>';

    $(styles).find('cellXfs').append(cellXfsHeader).append(cellXfsOdd).append(cellXfsEven);

    var cellXfIndexHeader = $(styles).find('cellXfs xf').length - 3;
    var cellXfIndexOdd = $(styles).find('cellXfs xf').length - 2;
    var cellXfIndexEven = $(styles).find('cellXfs xf').length - 1;

    // Apply styles based on row index
    $('row', sheet).each(function(rowIndex) {
        var rowStyleIndex = rowIndex === 0 ? cellXfIndexHeader : (rowIndex % 2 === 0 ? cellXfIndexOdd : cellXfIndexEven);
        $(this).find('c').each(function() {
            $(this).attr('s', rowStyleIndex);
        });
    });

    // Ensure all cells are filled with the chosen color, even if empty
    var maxRow = $('row', sheet).length;
    var maxCol = 0;
    $('row', sheet).each(function() {
        var colCount = $(this).find('c').length;
        if (colCount > maxCol) {
            maxCol = colCount;
        }
    });

    for (var rowIdx = 1; rowIdx <= maxRow; rowIdx++) {
        for (var colIdx = 1; colIdx <= maxCol; colIdx++) {
            var cellRef = String.fromCharCode(64 + colIdx) + rowIdx;
            if ($('row[r="' + rowIdx + '"] c[r="' + cellRef + '"]', sheet).length === 0) {
                $('row[r="' + rowIdx + '"]', sheet).append('<c r="' + cellRef + '"></c>');
            }
            var styleIndex = rowIdx === 1 ? cellXfIndexHeader : (rowIdx % 2 === 0 ? cellXfIndexOdd : cellXfIndexEven);
            $('c[r="' + cellRef + '"]', sheet).attr('s', styleIndex);
        }
    }

    // Find cells with dates and set the date format
    $('row', sheet).each(function(rowIndex) {
        $(this).find('c').each(function() {
            var cell = $(this);
            if (cell.text().match(/^\d{4}-\d{2}-\d{2}$/)) { // Simple date regex YYYY-MM-DD
                cell.attr('s', cellXfIndexEven); // Apply style for date cells
                cell.append('<v>' + cell.text() + '</v>'); // Wrap date in <v> tag
            }
        });
    });
}