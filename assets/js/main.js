$(function() {
    $( "#datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        yearRange: "-100:-13",
        firstDay: 1,
        hideIfNoPrevNext: true,
        monthNamesShort: [ "Sausis", "Vasaris", "Kovas", "Balandis", "Gegužė", "Birželis", "Liepa", "Rugpjūtis", "Rgsėjis", "Spalis", "Lapkritis", "Gruodis" ],
        dayNamesMin: [ "S", "P", "A", "T", "K", "Pn", "Š" ]
    });
})