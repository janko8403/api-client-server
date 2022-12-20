/**
 * Created by pawel on 21.03.2017.
 */
import {NotifyError, NotifySuccess} from './notify'
import {FileUpload}                 from './file-upload'
import * as dayjs                   from 'dayjs'
import {QuestionTypes}              from './question-types'
import t                            from './translate'
import Utils                        from './utils'

export class QuestionTemplates {
    private _urlQuestionsList: string

    set urlQuestionsList(value: string) {
        this._urlQuestionsList = value
    }

    /**
     * Inits UI events.
     */
    public initEvents(): void {
        // add a question
        $('#add-question a').click((e: any) => {
            return this.saveDialog(e)
        })

        // edit a question
        $('#questions').on('click', '.a-edit', (e: any) => {
            return this.saveDialog(e)
        })

        // delete a question
        $('#questions').on('click', '.a-delete', (e: any) => {
            return this.deleteQuestion(e)
        })

        // remove an answer from a question
        $('body').on('click', '.btn-remove-answer', (e: any) => {
            return this.deleteAnswer(e)
        })

        // add an answer to a question
        $('body').on('click', '.add-question-answer', (e: any) => {
            return this.addAnswer(e)
        })

        // add an answer to inspector single option question
        $('body').on('click', '.add-inspector-question-answer', (e: any) => {
            return this.addInspectorQuestionAnswer(e)
        })
    }

    /**
     * Refreshes question list.
     */
    private refreshQuestions(): void {
        $('#questions').load(this._urlQuestionsList)
    }

    /**
     * Creates add/edit question dialog.
     *
     * @param e
     * @returns {boolean}
     */
    private saveDialog(e: any): boolean {
        let id = null
        $.get($(e.currentTarget).attr('href'), (resp: any) => {
            // let size = (resp.type == 'extended' ? 'large' : '');
            let id = resp.q_id
            const dialog = bootbox.dialog({
                title: t.t('Pytanie') + ' ' + resp.title,
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
                            let form = $('#questionForm')
                            let validator = ValidatorFactory.factory(resp.type_key)
                            if (validator.validate(form)) {
                                $.post(form.attr('action'), form.serializeArray(), (resp: any) => {
                                    if (resp.result) {
                                        new NotifySuccess(t.t('Pytanie zostało zapisane'))
                                        this.refreshQuestions()
                                        bootbox.hideAll()
                                    } else {
                                        $('.bootbox-body').html(resp.html)
                                        let filetypes = FileUpload.TYPE_JPG
                                        let url = '/question-templates/upload'
                                        if (id) {
                                            url = url + '/' + id
                                        }
                                        new FileUpload(
                                            'question-thumbnail',
                                            url,
                                            filetypes,
                                            true,
                                        )
                                    }
                                })
                            } else {
                                validator.displayErrors()
                            }

                            return false
                        },
                    },
                },
            })
            Utils.fixBootbox(dialog, 'xl')

            let filetypes = FileUpload.TYPE_JPG
            let url = '/question-templates/upload'
            if (id) {
                url = url + '/' + id
            }
            new FileUpload(
                'question-thumbnail',
                url,
                filetypes,
                true,
            )
        })

        return false
    }

    /**
     * Handles question deletion (with confirmation).
     *
     * @param e
     * @returns {boolean}
     */
    private deleteQuestion(e: any): boolean {
        bootbox.confirm(
            t.t('Czy na pewno chcesz usunąć powiązanie pytania z szablonem?'),
            (result: any) => {
                if (result) {
                    $.post($(e.currentTarget).attr('href'), (resp: string) => {
                        if (resp == 'ok') {
                            new NotifySuccess(t.t('Pytanie zostało usunięte'))
                            this.refreshQuestions()
                        } else {
                            new NotifyError()
                        }
                    })
                }
            },
        )

        return false
    }

    /**
     * Handles anwser deletion (with confirmation).
     *
     * @param e
     * @returns {boolean}
     */
    private deleteAnswer(e: any): boolean {
        bootbox.confirm(
            t.t('Czy na pewno chcesz usunąć odpowiedź?'),
            (result: any) => {
                if (result) {
                    // removing an anwser for single option inspector question
                    let parentGroup = $(e.currentTarget).parents('.input-group:first')
                    if (parentGroup.length) {
                        parentGroup.remove()
                    } else {
                        // removing question answer
                        $(e.currentTarget).parents('div.row:first').remove()
                    }
                }
            },
        )

        return false
    }

    /**
     * Load new answer template.
     *
     * @param e
     * @returns {boolean}
     */
    private addAnswer(e: any): boolean {
        $.get($(e.currentTarget).attr('href'), (resp: string) => {
            $('#answers-container').append(resp)
        })

        return e.preventDefault()
    }

    private addInspectorQuestionAnswer(e: any): boolean {
        let elem = $(e.currentTarget)

        if (!elem.data('id')) {
            elem.data('id', dayjs().unix())
        }

        let textTpl = `
            <div class="input-group form-group">
                <input type='text' class='form-control' name='new_question_answers[%d][]' value='%s' />
                <div class="input-group-btn">
                    <button class="btn btn-danger btn-remove-answer" type="button">
                        <i class="fa fa-trash" />
                    </button>
                </div>
            </div>
        `
        let value = elem.parent().prev().val()
        let answersContainer = elem.parents('.row:first').find('.inspector-question-answers')

        if ($.trim(<string>value) != '') {
            // @ts-ignore
            answersContainer.append(sprintf(textTpl, elem.data('id'), value))

            $(e.currentTarget).parent().prev().val('')
        }

        return false
    }
}

class ValidatorBase {
    protected errors: Array<string>

    constructor() {
        this.errors = []
    }

    validate(form: any): boolean {
        return true
    }

    displayErrors(): void {
        for (let e of this.errors) {
            new NotifyError(e)
        }

        this.errors = []
    }
}

class ValidatorSingleOption extends ValidatorBase {
    validate(form: any): boolean {
        let valid: boolean = true

        $(':text[name^=new_answers], :text[name^=answers]').each((i: number, elem: any) => {
            if ($.trim(<string>$(elem).val()) == '' && valid) {
                this.errors.push(t.t('Należy wpisać treść wszystkich odpowiedzi'))
                valid = false
            }
        })

        if ($.trim(<string>$('#questionForm :text[name=value]').val()) == '') {
            this.errors.push(t.t('Należy wpisać nazwę szablonu'))
            valid = false
        }

        return valid
    }
}

class ValidatorInspector extends ValidatorBase {
    validate(form: any): boolean {
        let valid: boolean = true

        $(':text[name^=new_description], :text[name^=description]').each((i: number, elem: any) => {
            if ($.trim(<string>$(elem).val()) == '' && valid) {
                this.errors.push(t.t('Należy wpisać treść wszystkich pytań'))
                valid = false
            }
        })

        if ($.trim(<string>$('#questionForm :text[name=value]').val()) == '') {
            this.errors.push(t.t('Należy wpisać nazwę szablonu'))
            valid = false
        }

        $(':text[name^=new_question_answer], :text[name^=question_answer]').each((i: number, elem: any) => {
            if ($.trim(<string>$(elem).val()) == '') {
                this.errors.push(t.t('Należy wpisać przynajmniej jedną odpowiedź dla pytania jeden z wielu'))
                valid = false
            }
        })

        return valid
    }
}

class ValidatorPromoter extends ValidatorBase {
    validate(form: any): boolean {
        let valid: boolean = true

        if ($.trim(<string>$('#questionForm :text[name=value]').val()) == '') {
            this.errors.push(t.t('Należy wpisać nazwę szablonu'))
            valid = false
        }

        if ($('#promoterProductsContainer .product-row').length == 0) {
            this.errors.push(t.t('Należy wprowadzić przynajmniej jeden produkt'))
            valid = false
        }

        return valid
    }
}

class ValidatorFactory {
    static factory(type: string): ValidatorBase {
        switch (type) {
            case QuestionTypes.MULTI_OPTION:
            case QuestionTypes.SINGLE_OPTION:
                return new ValidatorSingleOption()
            case QuestionTypes.INSPECTOR:
                return new ValidatorInspector()
            case QuestionTypes.PROMOTER:
                return new ValidatorPromoter()
            default:
                return new ValidatorBase()
        }
    }
}

(<any>window).QuestionTemplates = QuestionTemplates

export default QuestionTemplates