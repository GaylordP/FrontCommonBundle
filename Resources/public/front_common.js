import $ from 'jquery'

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

class FrontCommon
{
    constructor()
    {
        this.$modal = null
        this.modalIsOpen = false
        let self = this

        $('body').on('click', 'a[data-front-common-ajax]', function(e) {
            e.preventDefault()

            self.ajaxLink($(this))
        })

        $('body').on('submit', 'form[data-form-ajax]', function(e) {
            e.preventDefault()

            self.ajaxFormSubmit($(this))
        })
    }

    ajaxLink($element) {
        let self = this

        $.ajax({
            url: $element.attr('href'),
            dataType: 'json',
            complete: function(xhr) {
                self.ajaxJsonResponse(xhr.responseJSON)
            },
        })
    }

    ajaxFormSubmit($form) {
        let self = this

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            complete: function(xhr) {
                $form[0].reset()
                self.ajaxJsonResponse(xhr.responseJSON)
            },
        })
    }

    ajaxJsonResponse(json)
    {
        let self = this

        if (true === Array.isArray(json)) {
            for (let i = 0; i < json.length; i++) {
                self.ajaxJsonResponse(json[i])
            }

            return
        }

        let $target = $(json.target)

        if ('after' === json.action || 'append' === json.action || 'before' === json.action || 'prepend' === json.action) {
            let $html = $(json.html)
            let $layer = $('<div class="bg-success" style="width: 100%; height: 100%; position: absolute; opacity: 0.25"></div>')
            $html.append($layer)

            switch (json.action) {
                case 'after':
                    $target.after($html)
                break;
                case 'append':
                    $target.append($html)
                break;
                case 'before':
                    $target.before($html)
                break;
                case 'prepend':
                    $target.prepend($html)
                break;
            }

            if ($html.attr('data-toggle') === 'tooltip') {
                $html.tooltip()
            }
            $html.find('[data-toggle="tooltip"]').tooltip()

            if (true === self.modalIsOpen) {
                self.$modal.animate({
                    scrollTop: self.$modal.find('.modal-dialog').height()
                }, 1000)
            }

            setTimeout(function () {
                $layer.fadeOut()
            }, 5000)
        } else if ('html' === json.action) {
            let $html = $(json.html)

            $target.html($html)

            if ($html.attr('data-toggle') === 'tooltip') {
                $html.tooltip()
            }
            $html.find('[data-toggle="tooltip"]').tooltip()
        } else if ('replace' === json.action) {
            let $html = $(json.html)

            $target.replaceWith($html)

            if ($html.attr('data-toggle') === 'tooltip') {
                $html.tooltip()
            }
            $html.find('[data-toggle="tooltip"]').tooltip()
        } else if ('show-modal' === json.action) {
            self.modal(json.title, json.body)
        }
    }

    modal(title, body) {
        let self = this

        if (null === this.$modal) {
            this.$modal = $('<div class="modal fade" id="frontCommonModal" tabindex="-1" role="dialog" aria-labelledby="frontCommonModalTitle" aria-hidden="true">\n' +
            '    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">\n' +
            '        <div class="modal-content">\n' +
            '            <div class="modal-header">\n' +
            '                <h5 class="modal-title"></h5>\n' +
            '                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
            '                    <i class="fas fa-times"></i>\n' +
            '                </button>\n' +
            '            </div>\n' +
            '            <div class="modal-body"></div>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n')

            $('body').prepend(this.$modal)

            this.$modal.on('show.bs.modal', function (e) {
                self.modalIsOpen = true
            })

            this.$modal.on('hidden.bs.modal', function (e) {
                self.modalIsOpen = false
            })
        }

        this.$modal.find('.modal-title').html(title);
        this.$modal.find('.modal-body').html(body);
        this.$modal.modal('show')
        this.$modal.find('[data-toggle="tooltip"]').tooltip()
    }
}

global.FrontCommon = new FrontCommon()
