import {NotifyError, NotifySuccess} from './notify'
import t                            from './translate'
import {AutocompleteSingle}         from './functions'
import Utils                        from './utils'
import Event = JQuery.Event
import ClickEvent = JQuery.ClickEvent
import ChangeEvent = JQuery.ChangeEvent

export class User {
    initEvents(): void {
        $('#tikrowUserTable > tbody > tr').click((e: any) => {
            if ($(e.target).is('td')) {
                window.location.href = $(e.currentTarget).find('.a-details').attr('href')
                return false
            }
        })

        $('#content').on('click', '.a-changePassword', (e: any) => {
            e.preventDefault()
            return this.changePassword(e)
        })
        $('#content').on('click', '#a-send-notifications', (e: any) => {
            e.preventDefault()
            return this.sendNotifications(e)
        })
        $('#content').on('click', '.a-update-status', (e) => {
            e.preventDefault()
            return this.updateStatus(e)
        })

        // remote login
        $('#content').on('click', '.a-remote-login', (e) => {
            e.preventDefault()
            return this.remoteLogin(e)
        })
        $('body').on('click', '#remote-login-no', () => {
            bootbox.hideAll()
        })
        $('#content').on('click', '.a-confirm', async (e) => {
            e.preventDefault()
            const href = $(e.currentTarget).attr('href')
            const resp = await $.post(href)

            if (resp == 'ok') {
                await $('#content').load(location.href)
                new NotifySuccess('Prośba została zatwierdzona')
            } else {
                new NotifyError()
            }
        })
        $('body').on('click', '#btn-remote-login', async (e) => {
            e.preventDefault()
            const href = $(e.currentTarget).attr('href')
            const resp: { redirect_url: string } = await $.post(href)

            bootbox.hideAll()
            window.open(resp.redirect_url)
        })

        // delete application
        $('#content').on('click', '#a-delete-application', (e) => {
            const href = $(e.currentTarget).attr('href')

            bootbox.prompt({
                inputType: 'number',
                title: t.t('Wprowadź ID partnera'),
                callback(result) {
                    if (result) {
                        window.location.href = `${href}/${result}`
                    }
                },
            })

            return false
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
    }

    initUserDataEvents(): void {
        document.addEventListener('contextmenu', event => event.preventDefault())
        $('#content').on('click', '.show-modal', (e: any) => {
            e.preventDefault()
            let html = '<img src=\'' + $(e.currentTarget).attr('href') + '\' class=\'img-responsive\' />'

            bootbox.alert(html)

            return false
        })

        $('#content').on('click', '#a-messages', (e: any) => {
            const href = $(e.currentTarget).attr('href')

            $.get(href, r => bootbox.alert(r))

            return false
        })

        $('body').on('submit', '#frm-new-message', (e: any) => {
            const frm = $(e.currentTarget)
            const action = frm.attr('action')

            $.post(action, frm.serializeArray(), (resp: string) => {
                if (resp == 'ok') {
                    new NotifySuccess('Wiadomość została wysłana')
                    bootbox.hideAll()
                }
            })

            return false
        })

        $('body').on('change', 'select[name=notification]', (e: ChangeEvent) => {
            const opt = $(e.currentTarget).find(':selected')
            const val = opt.val()
            const comment = $('input[name=comment]').val()
            const text = val ? opt.text() + ` ${comment}` : ''

            $(':text[name="userData[1]"]').val(text)
            if (val) {
                $('select[name=accept-last-data]').val('2')
            } else {
                $('select[name=accept-last-data]').val('')
            }
        })

        $('body').on('change', 'input[name=comment]', (e: Event) => {
            $('select[name=notification]').trigger('change')
        })
    }

    initEventsCompetences(): void {
        // adding a competence
        $('#content').on('click', '#btn-add-competence', (e: any) => {
            let url = $(e.currentTarget).data('url')
            let competenceId = $('#product').val()

            if (competenceId) {
                $.post(url, {competence: competenceId}, (resp: any) => {
                    if (resp.result) {
                        new NotifySuccess('Kompetencja została zapisana.')
                        $('#content').load(location.href, () => {
                            new AutocompleteSingle('product', '/autocomplete/product')
                        })
                    } else {
                        new NotifyError(resp.msg ? resp.msg : null)
                    }
                })
            }
            return false
        })

        $('#content').on('click', '.a-edit', (e: any) => {
            let href = $(e.currentTarget).attr('href')

            $.get(href, (resp: any) => {
                bootbox.dialog({
                    title: t.t('Edycja kompetencji'),
                    message: resp.html,
                    buttons: {
                        cancel: {
                            label: t.t('Zamknij'),
                        },
                        save: {
                            label: t.t('Zapisz'),
                            className: 'btn btn-sl',
                            callback: () => {
                                let form = $('#userProductForm')
                                $.post(form.attr('action'), form.serializeArray(), (resp: any) => {
                                    if (resp.result) {
                                        new NotifySuccess(t.t('Kompetencja została zapisana.'))
                                        bootbox.hideAll()
                                        $('#content').load(location.href, () => {
                                            new AutocompleteSingle('product', '/autocomplete/product')
                                        })
                                    } else {
                                        $('.bootbox-body').html(resp.html)
                                    }
                                })
                                return false
                            },
                        },
                    },
                })

                Utils.initDOM(gLocale)
            })

            return false
        })
    }

    private changePassword(e: any): any {
        let id = null
        let target = $(e.currentTarget)
        $.get($(e.currentTarget).attr('href'), (resp: any) => {
            const dialog = bootbox.dialog({
                title: t.t('Zmiana hasła użytkownika.'),
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
                            let form = $('#changePasswordForm')
                            $.post(form.attr('action'), form.serializeArray(), (resp: any) => {
                                if (resp.result) {
                                    new NotifySuccess(t.t('Dane zostały zapisane'))
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

    private sendNotifications(e: any): any {
        const href = $(e.currentTarget).attr('href') + window.location.search

        $.get(href, (resp: string) => {
            bootbox.dialog({
                title: t.t('Wysyłka powiadomień'),
                message: resp,
                buttons: {
                    cancel: {
                        label: t.t('Zamknij'),
                    },
                    save: {
                        label: t.t('Wyślij'),
                        className: 'btn btn-sl',
                        callback: () => {
                            const mid: string = $('#message-id').val() as string

                            if (!mid.length) {
                                new NotifyError('Proszę wybrać wartość')
                                return false
                            }

                            $.post(href, $('.modal-body form').serializeArray(), (resp: string) => {
                                if (resp == 'ok') {
                                    new NotifySuccess(t.t('Powiadomienia zostały wysłane'))
                                    bootbox.hideAll()
                                } else {
                                    new NotifyError()
                                }
                            })

                            return false
                        },
                    },
                },
            })
        })
    }

    private async remoteLogin(e: ClickEvent) {
        const href = $(e.currentTarget).attr('href')
        let intervalID: NodeJS.Timer

        const clearInt = () => {
            if (intervalID) {
                clearInterval(intervalID)
            }
        }

        const refresh = async (url: string) => {
            const resp: { confirmed: boolean, html: string } = await $.get(url)
            $('.bootbox-body').html(resp.html)
            if (resp.confirmed) {
                clearInt()
            }
        }

        bootbox.prompt({
            title: t.t('Logowanie na konto partnera'),
            inputType: 'textarea',
            async callback(reason) {
                if (!$.trim(reason).length) {
                    new NotifyError('Wprowadź powód logowania na konto partnera.')
                } else {
                    const resp: { refresh_url: string } = await $.post(href, {reason})
                    const alert = bootbox.alert(t.t('Wczytywanie...'))
                    alert.on('hidden.bs.modal', () => clearInt())

                    await refresh(resp.refresh_url)
                    intervalID = setInterval(async () => {
                        await refresh(resp.refresh_url)
                    }, 5000)
                }
            },
        })
    }

    private updateStatus(e: ClickEvent): any {
        $.get($(e.currentTarget).attr('href'), (resp: string) => {
            const dial = bootbox.dialog({
                title: t.t('Zmiana statusu'),
                message: resp,
                buttons: {
                    cancel: {
                        label: t.t('Zamknij'),
                    },
                },
            })

            dial.on('shown.bs.modal', () => {
                $('.modal-body form').on('submit', async (e) => {
                    e.preventDefault()

                    // @ts-ignore
                    const resp = await $.post($(e.target).attr('action'), {status: e.originalEvent.submitter.value})
                    if (resp === 'ok') {
                        new NotifySuccess('Zmieniono status partnera')
                        location.href = location.href
                    }

                    bootbox.hideAll()
                })
            })
        })
    }
}

(<any>window).User = User