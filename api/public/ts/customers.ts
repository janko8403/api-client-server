// @ts-nocheck
import {NotifyError, NotifySuccess} from './notify'

interface IResponse {
    result: boolean
    html: string
}

export class Customers {
    private _urlShowMap: string = ''

    set urlShowMap(value: string) {
        this._urlShowMap = value
    }

    private _urlGeocode: string = ''

    set urlGeocode(value: string) {
        this._urlGeocode = value
    }

    initForm(): void {
        $('#content').on('click', '#btn-geocode', (e: any) => {
            $.get(this._urlGeocode, (resp: string) => {
                if (resp == 'ok') {
                    new NotifySuccess('Współrzędne punktu zostały zapisane')
                    window.location.href = location.href
                } else {
                    new NotifyError()
                }
            })
        })

        $('#content').on('click', '#btn-show-map', (e: any) => {
            $.get(this._urlShowMap, (resp: string) => {
                bootbox.alert(resp)
            })

            return false
        })
    }

    initList(): void {
        $('#customerTable > tbody > tr').click((e: any) => {
            if ($(e.target).is('td')) {
                window.location.href = $(e.currentTarget).find('.a-details').attr('href')
                return false
            }
        })

        // CSV/XLS file import
        $('#content').on('click', '.a-import', (e: any) => {
            let url = $(e.currentTarget).attr('href')

            $.get(url, (resp: string) => {
                let dialog = bootbox.dialog({
                    title: t.t('Import pliku'),
                    message: resp,
                    buttons: {
                        cancel: {
                            label: t.t('Zamknij'),
                            className: 'btn-default',
                        },
                        import: {
                            label: t.t('Importuj'),
                            className: 'btn-sl',
                            callback: () => {
                                // @ts-ignore
                                $('.bootbox-body input[type=file]').uploadifive('upload')

                                return false
                            },
                        },
                    },
                })
            })

            return false
        })

        $('body').on('click', '.a-scoring-details', async (e: Event) => {
            e.preventDefault()

            const url = $(e.target).attr('href')
            const resp = await $.get(url)

            bootbox.alert(resp)
        })

        $('body').on('click', '.a-create-message', async (e: Event) => {
            e.preventDefault()

            const url = $(e.currentTarget).attr('href')
            const message: IResponse = await $.get(url)

            bootbox.dialog({
                title: t.t('Komunikat do partnerów'),
                message: message.html,
                buttons: {
                    send: {
                        label: t.t('Wyślij'),
                        className: 'btn btn-sl',
                        callback() {
                            $.post(
                                url,
                                $('#messageForm').serializeArray(),
                                (resp: IResponse) => {
                                    if (!resp.result) {
                                        $('.modal-body').html(resp.html)
                                    } else {
                                        new NotifySuccess('Wiadmość została skierowana do wysyłki')
                                        bootbox.hideAll()
                                    }
                                },
                            )

                            return false
                        },
                    },
                    cancel: {
                        label: t.t('Anuluj'),
                        className: 'btn btn-default',
                    },
                },
            })
        })
    }
}

(<any>window).Customers = Customers