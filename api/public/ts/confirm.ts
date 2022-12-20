// @ts-nocheck
import {NotifyError, NotifySuccess} from './notify'
import Utils                        from './utils'

export class Confirm {
    constructor(url: string) {
        const modal = bootbox.confirm({
            message: t.t('Czy na pewno chcesz wykonać akcję?'),
            callback: (result: any) => {
                if (result) {
                    $.post(url, (resp: string) => {
                        if (resp == 'ok') {
                            new NotifySuccess(t.t('Operacja przebiegła pomyślnie.'))
                            location.reload()
                            //  $("#content").load(location.href, () => {
                            //      Utils.initDOM(gLocale);
                            //});
                        } else {
                            new NotifyError()
                        }
                    })
                }
            },
            size: 'small',
            buttons: {
                confirm: {
                    className: 'btn-sl',
                    label: 'Zatwierdź',
                },
                cancel: {
                    label: 'Anuluj',
                },
            },
        })
        Utils.fixBootbox(modal)
    }
}

(<any>window).Confirm = Confirm