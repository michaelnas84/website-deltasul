function hightlightme(item) {
    item.style.fill = "#090e1c"
    document.getElementById('text_' + item.id).style.opacity = "1"
}
function clicked(item) {
    document.getElementById(item.id).click()
    item.style.fill = "#090e1c"
    document.getElementById('custom-select').value = item.id
    document.getElementById('select2-custom-select-container').title = item.id
    document.getElementById('select2-custom-select-container').innerHTML = item.id.replace(/_/g, ' ')
    document.getElementById('scroll').click()
    if (item == 'todos') {
        document.getElementById('slick-track-tot').style.display = 'none'
        document.getElementById('slick-track-min1').style.display = 'flex'
        document.getElementById('slick-track-min2').style.display = 'flex'
        document.getElementById('slick-track-min3').style.display = 'flex'
    } else {
        document.getElementById('slick-track-tot').style.display = 'flex'
        document.getElementById('slick-track-min1').style.display = 'none'
        document.getElementById('slick-track-min2').style.display = 'none'
        document.getElementById('slick-track-min3').style.display = 'none'
    }
}
function unhighlightme(item) {
    item.style.fill = "#17264d"
    document.getElementById('text_' + item.id).style.opacity = "0"
}

function troca_option() {
    option_selected = document.getElementById('custom-select').value
    document.getElementById(option_selected).click()
    document.getElementById('scroll').click()
    if (option_selected == 'todos') {
        document.getElementById('slick-track-tot').style.display = 'none'
        document.getElementById('slick-track-min1').style.display = 'flex'
        document.getElementById('slick-track-min2').style.display = 'flex'
        document.getElementById('slick-track-min3').style.display = 'flex'
    } else {
        document.getElementById('slick-track-tot').style.display = 'flex'
        document.getElementById('slick-track-min1').style.display = 'none'
        document.getElementById('slick-track-min2').style.display = 'none'
        document.getElementById('slick-track-min3').style.display = 'none'
    }
}

function whatsapp() {
    alert('Aguardando WhatsApp de cada loja')
}

$('.btn').on('click', function () {
    var cat = $(this).attr('data-cad')
    if (cat == 'todos') {
        $('.card div').show()
    } else {
        $('.card div').each(function () {
            if (!$(this).hasClass(cat)) {
                $(this).hide()
            } else {
                $(this).show()
            }
        })
    }
})

$(document).ready(function () {
    $('.custom-select').select2()
})