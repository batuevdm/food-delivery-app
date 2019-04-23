$(document).ready(function () {

    $('#menu-btn').on('click', function () {
        $('.mobile-menu').toggleClass('opened');
    });

    $('#m-main-menu').on('click', function () {
        $('.menu').toggleClass('opened');
    });

    // Float menu

    function fixMenu() {
        let scroll = $(window).scrollTop();
        let _class = 'categories-fixed';
        let menu = $('#categories-large');
        if (scroll >= 160) {
            menu.addClass(_class)
                .css('top', '20px')
                .css('bottom', 'auto')
                .css('position', 'fixed');

            var pos = $('body').outerHeight() - $('.footer').outerHeight(true) - $('#categories-large').outerHeight() - 20;

            if (scroll >= pos) {
                menu.css('bottom', '0px')
                    .css('top', 'auto')
                    .css('position', 'absolute');
            }
        }
        else {
            menu.removeClass(_class)
                .css('top', 'auto')
                .css('bottom', 'auto')
                .css('position', 'static');
        }

    }

    fixMenu();
    $(document).on('scroll', function () {
        fixMenu();
    });

    let owl = $('.other-photos-carousel');
    if (owl.owlCarousel !== undefined) {
        owl.owlCarousel({
            loop: false,
            margin: 10,
            dots: false,
            responsive: {
                0: {
                    items: 2
                },
                576: {
                    items: 3
                }
            }
        });

        $('#carousel-button-left').click(function () {
            owl.trigger('prev.owl.carousel');
        });
        $('#carousel-button-right').click(function () {
            owl.trigger('next.owl.carousel');
        });
    }

    if ($.fancybox !== undefined) {
        $('[data-fancybox="product"]').fancybox({
            buttons: [
                "zoom",
                "fullScreen",
                "close"
            ],
            animationEffect: "zoom",
            transitionEffect: "zoom-in-out",
        });
    }

    function search(e) {
        e.preventDefault();
        let q = $("#search-text").val().trim();

        if (q === '') {
            $('#search-text').focus();
            return false;
        }

        window.location = '/products/search/?q=' + encodeURIComponent(q);
    }

    $('#search-button').on('click', search);

    $('#search-text').on('keydown', function (e) {
        let key = e.which;
        if (key === 13) search(e);
    });

    $('.to-cart').on('click', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        addToCart(id);
    });

    $('.main-add-to-cart').on('click', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let col = $('#col').val();
        addToCart(id, col);
    });

    function addToCart(id, col) {
        if (col === undefined) col = 1;
        if (col < 1) col = 1;

        $.ajax({
            url: '/ajax/cart/add/' + id + '/' + col
        }).done(function (data) {
            if (data.status === 'ok') {
                alert(data.message);
            }
            else {
                alert('Ошибка: ' + data.message);
            }
        }).fail(function (data) {
            console.log(data);
            alert('Ошибка');
        });
    }

    $(document).on('click', '.cart-product-delete', function (e) {
        // e.preventDefault();
        if(confirmDelete('удалить этот товар из корзины')) {
            var id = $(this).data('id');
            deleteFromCart(id);
        }
    });

    function deleteFromCart(id) {
        $.ajax({
            url: '/ajax/cart/delete/' + id
        }).done(function (data) {
            if (data.status === 'ok') {
                var products = data.products;
                var total = data.totalPrice;

                updateProducts(products, total);
            }
            else {
                alert('Ошибка: ' + data.message);
            }
        }).fail(function (data) {
            console.log(data);
            alert('Ошибка');
        });
    }

    function updateProducts(products, totalPrice) {
        var container = $('.cart-products-container');
        container.empty();
        for(var i in products) {
            var product = products[i];
            var price = (product.product.new_price !== null) ? '<span class="old-price">' + formatMoney(product.product.price, 0, '.', ' ') + '</span> ' + formatMoney(product.product.new_price, 0, '.', ' ') : formatMoney(product.product.price, 0, '.', ' ');
            container.append(`
            <div class="cart-product" id="product-${product.product.id}">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <img src="${product.product.main_photo}" alt="${product.product.name}" class="cart-product-photo">
                    </div>
                    <a href="/products/product/${product.product.id}" class="cart-product-name col-md-7 col-8">${product.product.name}</a>
                    <div class="cart-product-options col-md-3 col-12">
                        <div class="cart-product-delete" role="button" data-id="${product.product.id}"></div>
                        <div class="cart-product-price">
                            ${price} ₽
                        </div>
                        <div class="cart-product-col">
                            <div class="product-minus" role="button" data-id="${product.product.id}"></div>
                            <input type="number" class="product-col" data-id="${product.product.id}" id="col-${product.product.id}" min="1" max="${product.product.col}" value="${product.col}">
                            <div class="product-plus" role="button" data-id="${product.product.id}"></div>
                        </div>
                    </div>
                </div>
            </div>`);
        }
        if(products.length === 0) container.append('В Вашей корзине пока ничего нет');

        $('#totalPrice').text(formatMoney(totalPrice, 0, '.', ' '));
    }

    function changeCol(id, val) {
        let obj = $('#col-' + id);
        let col = parseInt(obj.val()) + val;
        let min = parseInt(obj.attr('min'));
        let max = parseInt(obj.attr('max'));
        if (col > max) col = max;
        if (col < min) col = min;

        $.ajax({
            url: '/ajax/cart/update/' + id + '/' + col
        }).done(function (data) {
            if (data.status === 'ok') {
                col = data.col;
                $('#totalPrice').text(formatMoney(data.totalPrice, 0, '.', ' '));
                obj.val(col);
            }
            else {
                alert('Ошибка: ' + data.message);
            }
        }).fail(function (data) {
            console.log(data);
            alert('Ошибка');
        });
    }

    $(document).on('click', '.product-plus', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        changeCol(id, 1);
    });

    $(document).on('click', '.product-minus', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        changeCol(id, -1);
    });

    $(document).on('change paste keyup', '.product-col', function (e) {
        let id = $(this).data('id');
        changeCol(id, 0);
    });

    $('#order-address').on('change', function() {
        if ($(this).val() === 'new') {
            $('#order-address-field').show();
        } else {
            $('#order-address-field').hide();
        }
    }).trigger('change');

});

function confirmDelete(ask) {
    return confirm('Вы действительно хотите ' + ask + '? Отменить действие будет невозможно');
}

function formatMoney(n, c, d, t) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d === undefined ? "." : d,
        t = t === undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
        j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}