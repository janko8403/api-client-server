import {NotifyError, NotifySuccess} from './notify'
import {Confirm}                    from './confirm'
import t                            from './translate'

declare global {
    let gLocale: string
    let gLanguage: string
}

export class Utils {
    private static urlExtendDictionary: string = '/settings/dictionaries-extend/add'

    public static initDOM(locale: string): void {
        // connect datepicker
        // $('input[type="date"]:not(.dont-modify)').attr('type', 'text')

        // const options = {
        //     display: {
        //         components: {clock: false},
        //     },
        // }
        // document.querySelectorAll('input[data-provide="datepicker"]:enabled').forEach((d) => {
        //     const dp = new TempusDominus(<HTMLElement>d, options)
        //     dp.dates.formatInput = function (date) {
        //         {
        //             return dayjs(date).format('YYYY-MM-DD')
        //         }
        //     }
        // })

        // connect masks
        // @ts-ignore
        $.applyDataMask('.mask')
    }

    public static setActivateEvents(): void {
        // activating/deactivating
        $('#content').on('click', '.a-activate, .a-deactivate', (e: any) => {
            let url = $(e.currentTarget).attr('href')

            new Confirm(url)

            return false
        })
    }

    public static loadContent(url: string, targetId: string) {
        $('#' + targetId).load(url)
    }

    public static initExtendDictionaryButtons() {
        $('.add-dictionary-value').click((e: any) => {
            let link = $(e.currentTarget)
            let dictionaryName = link.data('dictionary-name')
            let dictionaryId = link.data('dictionary-id')

            bootbox.prompt({
                title: t.t('Wprowadź nową wartość słownikową'),
                inputType: 'text',
                buttons: {
                    cancel: {
                        label: t.t('Zamknij'),
                    },
                    confirm: {
                        label: t.t('Dodaj'),
                        className: 'btn-sl',
                    },
                },
                callback: (result: any) => {
                    console.log(result)
                    if (result) {
                        let url = this.urlExtendDictionary + '/' + dictionaryId
                        $.post(url, {value: result}, (resp: any) => {
                            if (resp.result) {
                                const html = `<option value="${resp.id}" selected>${result}</option>`
                                $('#' + dictionaryName).append(html)
                                bootbox.hideAll()
                                new NotifySuccess('Wartość słownikowa została dodana')
                            } else {
                                new NotifyError(resp.msg)
                            }
                        })

                        return false
                    }
                },
            })

            return false
        })
    }

    public static initExtendPayerButtons() {
        $('.add-payer-value').click((e: any) => {
            let target = $(e.currentTarget)
            e.preventDefault()

            $.get('/payers/add', (resp: any) => {
                bootbox.dialog({
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
                                let form = $('#payerForm')

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
                                        Utils.initDOM(gLocale)
                                    }
                                })
                                return false
                            },
                        },
                    },
                }).on('shown.bs.modal', (e: any) => {
                    Utils.initDOM(gLocale)
                })
            })
        })
    }

    public static fixBootbox(modal: JQuery, size?: 'sm' | 'lg' | 'xl') {
        if (size) {
            modal.find('.modal-dialog').addClass(`modal-${size}`)
        }
        modal.find('.bootbox-close-button').addClass('btn-close').text('')
    }
}

(<any>window).Utils = Utils

export default Utils