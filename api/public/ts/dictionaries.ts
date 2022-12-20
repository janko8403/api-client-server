import {NotifyError, NotifySuccess} from './notify'
import t                            from './translate'
import Utils                        from './utils'

/**
 * Created by pawel on 05.07.2017.
 */
class Dictionaries {
    set urlList(value: string) {
        this._urlList = value
    }

    private _urlList: string

    initEvents(): void {
        $('#content').on('click', '.a-add, .a-edit', (e: any) => {
            let href = $(e.currentTarget).attr('href')

            $.get(href, (resp: any) => {
                const dialog = bootbox.dialog({
                    title: t.t('Wartość słownikowa'),
                    onEscape: true,
                    message: resp.html,
                    buttons: {
                        cancel: {
                            label: t.t('Zamknij'),
                        },
                        save: {
                            label: t.t('Zapisz'),
                            className: 'btn-sl',
                            callback: () => {
                                $.post(href, $('#dictionaryDetail').serializeArray(), (resp: any) => {
                                    if (resp.result) {
                                        new NotifySuccess(t.t('Wartość została zapisana.'))
                                        Utils.loadContent(this._urlList, 'content')
                                        bootbox.hideAll()
                                    } else {
                                        new NotifyError()
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

            return false
        })
    }
}

(<any>window).Dictionaries = Dictionaries