import {NotifyError} from './notify'
import t             from './translate'
import Utils         from './utils'

export class RecordPicker {
    /**
     * URL to send select/deselect row ajax request to.
     */
    selectUrl: string
    /**
     * Element which initiated modal creation.
     */
    element: any
    /**
     * Modal instance.
     */
    modal: any

    /**
     * @param elementId Modal's origin element id
     * @param selectUrl URL to send ajax select/deselect ajax requests to
     */
    constructor(elementId: string, selectUrl: string) {
        this.selectUrl = selectUrl
        this.element = $('#' + elementId)
        this.element.click(() => {
            this.openModal()

            return false
        })
    }

    /**
     * Selected records count.
     *
     * @type {number}
     * @private
     */
    private _selectedCount: number = 0

    get selectedCount(): number {
        return this._selectedCount
    }

    set selectedCount(value: number) {
        this._selectedCount = value
        this.updateSelectedCount()
    }

    /**
     * Cancel clicked
     */
    private _cancelled: boolean = false

    get cancelled(): boolean {
        return this._cancelled
    }

    /**
     * Optional callback called when dialog is being hidden.
     */
    private _hideCallback: () => {}

    set hideCallback(value: () => {}) {
        this._hideCallback = value
    }

    /**
     * Creates and opens modal. Sets up event listeners.
     */
    openModal(): void {
        this.selectedCount = 0
        this._cancelled = false

        // create modal and load content
        $.get(this.element.attr('href'), async (resp: any) => {
            // set picker state for possible rollback
            await $.post(this.element.attr('href') + '?save-state')

            this.modal = bootbox.dialog({
                message: resp.html,
                title: t.t('Wybierz rekordy') + ' (<span id=\'selectedRecordsCount\'>this.selectedCount</span>)',
                className: 'modal-record-picker',
                buttons: {
                    draw: {
                        label: t.t('Zaznacz znalezione'),
                        className: 'btn-info',
                        callback: () => {
                            const formData = $(this.modal).find('.form-search').serializeArray()
                            formData.push({
                                name: 'action',
                                value: 'addSelected',
                            })
                            $.post(this.selectUrl, formData, (resp: any) => {
                                if (resp.count >= 0) {
                                    this.selectedCount = resp.count
                                    $('.data-table > table > tbody > tr').each(function () {
                                        $(this).addClass('table-info')
                                    })
                                } else {
                                    new NotifyError()
                                }
                            })
                            return false
                        },
                    },
                    clear: {
                        label: t.t('Usuń wszystkie'),
                        className: 'btn-info',
                        callback: () => {
                            $.post(this.selectUrl, {'action': 'clearAll'}, (resp: any) => {
                                console.log(resp.count)
                                if (resp.count >= 0) {
                                    this.selectedCount = resp.count
                                    $('.data-table > table > tbody > tr').each(function () {
                                        $(this).removeClass('table-info')
                                    })
                                } else {
                                    new NotifyError()
                                }
                            })
                            return false
                        },
                    },
                    ok: {
                        label: t.t('Zapisz'),
                        className: 'btn-sl',
                    },
                    cancel: {
                        label: t.t('Wyjdź'),
                        callback: async () => {
                            this._cancelled = true
                            await $.post(this.selectUrl, {'action': 'cancelChanges'})
                        },
                    },
                },
            })

            this.modal.on('hidden.bs.modal', (e: any) => {
                if (this._hideCallback()) this._hideCallback()
            })
            this.modal.find('input[name="perPage"]').val(this.modal.find('#recordsPerPage').val())

            // fix bootbox for BS5
            Utils.fixBootbox(this.modal, 'xl')

            // clicking any link causes modal content ajax reload
            this.modal.on('click', 'a', (e: any) => {
                let href = $(e.currentTarget).attr('href')

                $.get(href, (resp: any) => {
                    this.modal.find('.bootbox-body').html(resp.html)
                    this.selectedCount = resp.count
                })

                return false
            })
            this.selectedCount = resp.count
            // when submitting search form reload modal content by ajax
            this.modal.on('submit', 'form', (e: any) => {
                let form = $(e.currentTarget)

                $.get(form.attr('action'), form.serializeArray(), (resp: any) => {
                    this.modal.find('.bootbox-body').html(resp.html)
                    this.selectedCount = resp.count
                })

                return false
            })

            // changing records per page causes modal content ajax reload
            this.modal.on('change', '#recordsPerPage', (e: any) => {
                this.modal.find('input[name=\'perPage\']').val($(e.currentTarget).val())
                this.modal.find('form').submit()
            })

            // clicking table's row selects/deselects it (and send ajax request to save that info)
            this.modal.on('click', '.data-table > table > tbody > tr', (e: any) => {
                let tr = $(e.currentTarget)

                if (tr.hasClass('table-info')) {
                    $.post(this.selectUrl, {
                        'action': 'remove',
                        'id': tr.data('id'),
                    }, (resp: string) => {
                        if (resp == 'ok') {
                            tr.removeClass('table-info')
                            this.selectedCount -= 1
                        } else {
                            new NotifyError()
                        }
                    })
                } else {
                    $.post(this.selectUrl, {
                        'action': 'add',
                        'id': tr.data('id'),
                    }, (resp: string) => {
                        if (resp == 'ok') {
                            tr.addClass('table-info')
                            this.selectedCount += 1
                        } else {
                            new NotifyError()
                        }
                    })
                }
            })
        })
    }

    /**
     * Updates selected records label in modal header.
     */
    updateSelectedCount(): void {
        $('#selectedRecordsCount').text(this.selectedCount)
    }
}

(<any>window).RecordPicker = RecordPicker