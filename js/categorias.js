
var slider = document.getElementById("myRange");
var output = document.getElementById("valor_filtro");
output.innerHTML = slider.value;
function justNumbers(text) {
    var numbers_without = text.split(",");
    var numbers = numbers_without[0].replace(/[^0-9]/g, '');
    return parseInt(numbers);
}

myRange.oninput = function () {
    $('.slick-track div').each(function () {
        if (this.id == 'shelf-item__best-price') {
            var valor_limpo = justNumbers($(this).attr('value'));
            console.log(valor_limpo);
            if (valor_limpo > slider.value) {
                $(this).parent().parent().parent().parent().parent().hide()
            } else {
                $(this).parent().parent().parent().parent().parent().show()
            }
        }
    })
    output.innerHTML = this.value;
}



$('.btn').on('click', function () {
    var cat = $(this).attr('data-cad')
    if (cat == 'todos') {
        if (this.id != "shelf-item__btns") {
            $('.slick-track div').show()
            $('.shelf-item__btns').attr("style", "display:  ")
        }
    } else {
        $('.slick-track div').each(function () {
            if (!$(this).hasClass(cat)) {
                if (this.id != "shelf-item__btns") {
                    $(this).hide()
                }
            } else {
                if (this.id != "shelf-item__btns") {
                    $(this).show()
                }
            }
        })
    }
})
