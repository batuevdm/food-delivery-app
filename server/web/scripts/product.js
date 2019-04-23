window.addEventListener('load', function () {
    function photoTemplate(i) {
        return "<div class=\"form-group\">\n" +
            "       <label for=\"photo-" + i + "\">Фото " + (i + 1) + "</label>\n" +
            "       <input type=\"file\" name=\"photos[]\" id=\"photo-" + i + "\" class=\"form-control-file\" accept=\"image/*\">\n" +
            "   </div>";
    }

    function specTemplate() {
        return "<div class=\"product-spec row mt-1\">\n" +
            "       <div class=\"col\">\n" +
            "           <input type=\"text\" name=\"spec-name[]\" class=\"form-control col spec-name\" list=\"specs-list\" placeholder=\"Имя\">\n" +
            "       </div>\n" +
            "       <div class=\"col\">\n" +
            "           <input type=\"text\" name=\"spec-value[]\" class=\"form-control col\" placeholder=\"Значение\">\n" +
            "       </div>\n" +
            "   </div>";
    }

    $('#add-photo').click(function () {
        let n = $(this).data('n');
        $(this).data('n', parseInt(n) + 1);
        $('#product-photos').append(photoTemplate(n));
    });

    $('#add-spec').click(function () {
        let specs = $('#product-specs').last();
        let name = specs.children().last().children().children().first();
        let value = specs.children().last().children().children().last();

        if (name.val() === '') {
            name.focus();
        } else if (value.val() === '') {
            value.focus();
        } else {
            specs.append(specTemplate());
        }
    });

    $('.delete-img').click(function (e) {
        e.preventDefault();

        let parent = $(this).parent();
        let id = $(this).data('id');

        $('#del-photos').append('<input type="hidden" name="del-photos[]" value="' + id + '"/>');
        parent.remove();
    });
});