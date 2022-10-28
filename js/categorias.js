
var slider = document.getElementById("myRange");
var output = document.getElementById("valor_filtro");
output.innerHTML = slider.value;

function justNumbers(text) {
    var numbers_without = text.split(",");
    var numbers = numbers_without[0].replace(/[^0-9]/g, '');
    return parseInt(numbers);
}

myRange.oninput = function () {
    $('.shelf-item__best-price').each(function () {
        var valor_limpo = justNumbers($(this).attr('value'));
        if (valor_limpo >= slider.value) {
            $(this).parent().parent().parent().parent().parent().hide()
        } else {
            $(this).parent().parent().parent().parent().parent().show()
        }
    })
    output.innerHTML = this.value;
}



$('.btn').on('click', function () {
    var cat = $(this).attr('data-cad')
    if (cat == 'todos') {
        $('.cat_ocult').show()
        return false
    }
    $('.cat_ocult').each(function () {
        if (!$(this).hasClass(cat)) {
            $(this).hide()
        } else {
            $(this).show()
        }
    })
})
