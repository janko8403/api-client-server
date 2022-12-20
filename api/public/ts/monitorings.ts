/**
 * Created by pawel on 14.04.2017.
 */
import {NotifyError, NotifySuccess} from './notify'
import t                            from './translate'

export class Monitorings {
    private _urlLoadQuestionList: string

    set urlLoadQuestionList(value: string) {
        this._urlLoadQuestionList = value
    }

    private _urlLoadQuestionAnswers: string

    set urlLoadQuestionAnswers(value: string) {
        this._urlLoadQuestionAnswers = value
    }

    private _urlClearConditions: string

    set urlClearConditions(value: string) {
        this._urlClearConditions = value
    }

    private _urlReloadQuestionList: string

    set urlReloadQuestionList(value: string) {
        this._urlReloadQuestionList = value
    }

    initEvents(): void {
        $('#question-list').on('click', '.btn-remove-question', (e: any) => {
            bootbox.confirm(
                t.t('Czy na pewno chcesz usunąć pytanie?'),
                (result: any) => {
                    if (result) {
                        $(e.currentTarget).parents('tr').remove()
                    }
                },
            )

            return false
        })

        $('#question-list').on('click', '.a-conditions', (e: any) => {
            let href = $(e.currentTarget).attr('href')


            $.get(href, (resp: any) => {
                bootbox.dialog({
                    title: t.t('Pytanie warunkowe'),
                    size: 'large',
                    onEscape: true,
                    message: resp.html,
                    buttons: {
                        cancel: {
                            label: t.t('Zamknij'),
                        },
                        delete: {
                            label: t.t('Usuń warunki'),
                            className: 'btn btn-danger',
                            callback: () => {
                                bootbox.confirm(
                                    t.t('Czy na pewno chcesz usunąć wszystkie warunki do pytania'),
                                    (result: any) => {
                                        if (result) {
                                            $.post(this._urlClearConditions, (resp: string) => {
                                                if (resp == 'ok') {
                                                    new NotifySuccess(t.t('Warunki zostały usunięte.'))
                                                    bootbox.hideAll()
                                                } else {
                                                    new NotifyError()
                                                }
                                            })
                                        }
                                    },
                                )

                                return false
                            },
                        },
                        save: {
                            label: t.t('Zapisz'),
                            className: 'btn btn-sl',
                            callback: () => {
                                let valid = true

                                // validate form
                                if (
                                    $('#dependentQuestionTextAnswer').is(':visible')
                                    && $.trim($('#dependentQuestionTextAnswer').val() as string) == ''
                                ) {
                                    new NotifyError(t.t('Proszę wpisać odpowiedź'))
                                    valid = false
                                } else if (
                                    $('#conditionalQuestionLogic').is(':visible')
                                    && $(':checkbox[name=\'monitoring-question-joint[conditionalQuestionAnswers][]\']:checked').length == 0
                                ) {
                                    new NotifyError(t.t('Proszę zaznaczyć przynajmniej jedną opcję'))
                                    valid = false
                                }

                                if (valid) {
                                    let form = $('#conditionalQuestionForm')

                                    $.post(form.attr('action'), form.serializeArray(), (resp: any) => {
                                        if (resp.result) {
                                            new NotifySuccess('Pytanie warunkowe zostało zapisane.')
                                            bootbox.hideAll()
                                        } else {
                                            new NotifyError()
                                        }
                                    })
                                }

                                return false
                            },
                        },
                    },
                }).on('hidden.bs.modal', (e: any) => {
                    this.loadSavedQuestionList()
                })
            })

            return false
        })

        $('#content').on('change', '.txt-commission-rate', (e: any) => {
            let no = $(e.currentTarget).data('no')
            let value = parseFloat(<string>$(e.currentTarget).val()) / 100
            value = value / (1 - value) + 1
            let rate = Math.round(value * 100) / 100

            $(`input[name='monitoring[commissionRate${no}]']`).val(rate)
        })
    }

    initSortable(): void {
        // @ts-ignore
        // $('.table-sorted').sortable({
        //     containerSelector: 'table',
        //     itemPath: '> tbody',
        //     itemSelector: 'tr',
        //     handle: '.handle',
        //     placeholder: '<tr class="placeholder"/>',
        // })
    }

    loadQuestionList(): void {
        $('#question-list').load(
            this._urlLoadQuestionList,
            () => {
                this.initSortable()
            },
        )
    }

    loadSavedQuestionList(): void {
        $('#question-list').load(
            this._urlReloadQuestionList,
            () => {
                this.initSortable()
            },
        )
    }

    initConditionalForm(): void {
        this.hideFields()

        this.loadAnswers($('#dependentQuestion'))
    }

    hideFields(): void {
        $('#textAnswerContainer').hide()
        $('#optionAnswerContainer').hide()
    }

    loadAnswers(e: any): void {
        let id = $(e).find(':selected').val()

        if (id) {
            this.hideFields()
            $.get(this._urlLoadQuestionAnswers, {id: id}, (resp: any) => {
                if (resp.count) {
                    $('#optionAnswerContainer').show()
                    $('#optionAnswerContainer').find('.form-group:eq(1) div').html(resp.html)
                } else {
                    $('#textAnswerContainer').show()
                }
            })
        }
    }
}

(<any>window).Monitorings = Monitorings
