/**
 * Created by mdomarecki on 03.07.17.
 */

import {NotifyError, NotifySuccess} from './notify'
import t                            from './translate'
import Utils                        from './utils'

export class Configuration {
    initEvents(): void {

        $('#content').on('click', '.edit-sequence', (e: any) => {
            e.preventDefault()
            return this.saveDialog(e)
        })

        $('#content').on('click', '.subresource-edit', (e: any) => {
            e.preventDefault()
            return this.saveDialog(e)

        })

        $('#content').on('click', '.form-close', (e: any) => {
            e.preventDefault()
            $('.data-scroll').html('')
            $('.data-scroll').hide()
        })
        $('#content').on('click', '.resource-edit-position', (e: any) => {
            e.preventDefault()
            return this.saveDialog(e)
        })
        $('#content').on('click', '.form-save', (e: any) => {
            e.preventDefault()
            let form = $('#editForm')

            $.post(form.attr('action'), form.serializeArray(), (resp: any, e: any) => {
                if (resp.result) {
                    new NotifySuccess(t.t('Dane zostały zapisane'))
                    $('.data-scroll').html('')
                    $('.data-scroll').hide()

                } else {
                    $('.data-scroll').html(resp.html)
                }
            })
        })
        $('#content').on('change', '.set-default-values', (e: any) => {
            e.preventDefault()
            let target = $(e.currentTarget)
            let defaultValue = target.val()
            target.closest('tr').find('td select').each(function () {
                $(this).val(defaultValue)
            })
        })
        $('#content').on('click', '.resource-show-submenu', (e: any) => {
            e.preventDefault()
            let clickedElement = $(e.currentTarget)
            let container = clickedElement.closest('tr').next('.config-details')
            let td = container.find('td')

            let href = clickedElement.attr('href')
            if (td.html() == '') {
                $.get(href, (resp: any) => {
                    if (resp.result) {
                        td.html(resp.html)
                        container.show('slow')
                    } else {
                        new NotifyError('Brak danych niższego poziomu.')
                    }
                })
            } else {
                container.hide()
                td.html('')
            }
        })

        $('#content').on('click', '.edit-privilages', (e: any) => {
            e.preventDefault()
            $.get($(e.currentTarget).attr('href'), (resp: any) => {
                $('.data-scroll').show('slow')
                $('.data-scroll').html(resp.html)
            })
        })
    }

    private saveDialog(e: any) {
        let id = null
        let target = $(e.currentTarget)
        $.get($(e.currentTarget).attr('href'), (resp: any) => {
            const dialog = bootbox.dialog({
                size: 'large',
                message: resp.html,
                buttons: {
                    cancel: {
                        label: t.t('Zamknij'),
                    },
                    save: {
                        label: t.t('Zapisz'),
                        className: 'btn btn-sl',
                        callback: () => {
                            let form = $('#editForm')
                            $.post(form.attr('action'), form.serializeArray(), (resp: any) => {
                                if (resp.redirect) {
                                    window.location.href = resp.redirect
                                }
                                if (resp.result) {
                                    new NotifySuccess(t.t('Dane zostały zapisane'))
                                    target.closest('table').parent().html(resp.html)

                                    bootbox.hideAll()
                                } else {
                                    $('.bootbox-body').html(resp.html)
                                }
                            })
                            return false
                        },
                    },
                },
            })

            Utils.fixBootbox(dialog)
        })
    }
}

(<any>window).Configuration = Configuration