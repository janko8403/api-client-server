/**
 * Created by pawel on 15.11.2016.
 */

import t          from './translate'
import * as dayjs from 'dayjs'

$(function () {
    $('button[name="save"]').on('click', function () {
        $('#bodyLoadingOverlay').show()
    })

    // @ts-ignore
    $('[data-toggle="tooltip"]').tooltip()

    // changing records per page causes page reload
    $('#recordsPerPage').change((e: any) => {
        let form = $('.form-search')

        form.find('input[name=\'perPage\']').val($(e.currentTarget).val())
        form.submit()
    })

    $(document).find('input[name=\'perPage\']').val($(document).find('#recordsPerPage').val())

    $(document).on('click', '.a-image-href', function () {
        let src = ''
        src = ($(this).attr('src')) ? $(this).attr('src') : $(this).attr('src-href')
        src += '?' + dayjs().unix()
        bootbox.alert('<img src=\'' + src + '\' class=\'img-responsive\' />')
        return false
    })
    $('.mobile-menu-close').on('click', function () {
        $('#overlay').fadeOut(330)
        $('.mobile-sidebar').fadeOut(660)

    })

    $('.mobile-menu-hamburger').on('click', function () {

        $('.mobile-sidebar').fadeIn(330)
        $('#overlay').fadeIn(660)
    })

    $('.tripple-dot').on('click', function () {
        $('#overlay').fadeIn(330)
        $('#right-sidebar').fadeIn(660)
    })
    $('.right-sidebar-close').on('click', function () {
        $('#overlay').fadeOut(330)
        $('#right-sidebar').fadeOut(660)
    })
    $('.go-back').on('click', function () {
        history.go(-1)
        return false
    })

})

/***** Autocomplete Select class */

export class Autocomplete {
    private urlChildDictionaryLoadValues: string = '/autocomplete/dependant-values'

    constructor(selectId: string, remoteDataUrl?: string) {
        let element = $('#' + selectId)
        let config: { placeholder: string; minimumInputLength: number, theme: string, ajax?: any } = {
            placeholder: t.t('wyszukaj'),
            minimumInputLength: 0,
            theme: 'bootstrap-5',
        }

        if (remoteDataUrl) {
            config.ajax = {
                url: remoteDataUrl,
                datType: 'json',
                delay: 250,
                data: (params: any) => {
                    return {
                        q: params.term,
                    }
                },
                processResults: (data: any, params: any) => {
                    return {
                        results: data,
                    }
                },
            }
            config.minimumInputLength = 3
        }

        // @ts-ignore
        element.select2(config)

        element.on('select2:close', (e: any) => {
            let count = $(e.currentTarget).find(':selected').length
            this.displaySummary(e.currentTarget, count)
        })

        this.displaySummary(element, element.find(':selected').length)

        if (element.is('[data-child-dictionary-id]')) {
            element.change(() => {
                this.loadChildDictionaryValues(element)
            })
            this.loadChildDictionaryValues(element)
        }
    }

    private displaySummary(select: any, count: number): void {
        $(select).next('.select2-container').find('.select2-search__field').attr('placeholder', t.t('Wybrano') + ': ' + count)
    }

    private loadChildDictionaryValues(element: any): void {
        let values = element.val()
        let dictionary = element.data('child-dictionary-id')
        let url = this.urlChildDictionaryLoadValues + '/' + dictionary

        $.get(url, {'values': values}, (resp: string) => {
            let childElement = $('#' + element.data('child-dictionary-name'))
            let childValues = childElement.val() as string[]
            let count = resp != '' ? childValues.length : 0
            childElement.html(resp).val(childValues)

            setTimeout(() => {
                this.displaySummary(childElement, count)
            }, 10)
        })
    }
}

export class AutocompleteSingle {
    public constructor(elementId: string, remoteDataUrl?: string, autocompleteData?: any, parent?: string, tags: boolean = false) {
        let config: {
            theme: string;
            placeholder: { id: string; placeholder: string };
            allowClear: boolean;
            minimumInputLength: number;
            tags: boolean
            ajax?: any;
        } = {
            allowClear: true,
            placeholder: {
                id: '',
                placeholder: '',
            },
            minimumInputLength: 3,
            tags,
            theme: 'bootstrap-5',
        }
        let element = $('#' + elementId)

        if (parent) {
            // @ts-ignore
            config.dropdownParent = $(parent)
        }

        if (remoteDataUrl) {
            config.ajax = {
                url: remoteDataUrl,
                datType: 'json',
                delay: 250,
                data: (params: any) => {
                    return {
                        q: params.term,
                    }
                },
                processResults: (data: any, params: any) => {
                    return {
                        results: data,
                    }
                },
            }
        }

        if (autocompleteData) {
            if (autocompleteData[elementId]) {
                $.get(remoteDataUrl, {id: autocompleteData[elementId]}, (resp: any) => {
                    $(resp).each((i: any, elem: any) => {
                        let option = `<option value="${elem.id}">${elem.text}</option>`
                        element.append(option)
                    })
                    element.val(autocompleteData[elementId])
                })
            }
        }

        // @ts-ignore
        element.select2(config)
    }
}

export class ComplexDictionary {
    private urlChildDictionaryLoadValues: string = '/autocomplete/dependant-values'

    public constructor(elementId: string) {
        let element = $('#' + elementId)

        if (element.is('[data-child-dictionary-id]')) {
            element.change(() => {
                this.loadChildDictionaryValues(element)
            })
            this.loadChildDictionaryValues(element)
        }
    }

    private loadChildDictionaryValues(element: any): void {
        let values = element.val()
        let dictionary = element.data('child-dictionary-id')
        let url = this.urlChildDictionaryLoadValues + '/' + dictionary

        $.get(url, {'values': values}, (resp: string) => {
            let dictionaryName = element.data('child-dictionary-name')
            let childElement = $('#' + dictionaryName)

            if (!childElement.length) {
                childElement = $('#' + dictionaryName + 's')
            }
            if (!childElement.length) {
                childElement = $('#' + dictionaryName.substring(0, dictionaryName.length - 1))
            }
            let childValues = childElement.val()

            childElement.html(resp).val(childValues)
        })
    }
}

export class Log {
    static urlLog: string = '/log'

    static linkClicks(container: string): void {
        Log.logClick(container, 'link')
    }

    static logClick(container: string, logUrl: string): void {
        $('body').on('click', container, (e: any) => {
            let href = $(e.currentTarget).attr('href')
            let url = this.urlLog + '/' + logUrl
            $.post(url, {details: href}, () => {
            })
        })
    }

    static pageLoad(): void {
        let url = this.urlLog + '/page-load'
        let loc = location.pathname + location.search
        $.post(url, {details: loc})
    }

    static log(type: number, details: string): void {
        let url = `${this.urlLog}/log/${type}`
        $.post(url, {details})
    }
}

export function goTo(url: string, e: Event): void {
    e.preventDefault()
    e.stopPropagation()
    window.location.href = url
}

export function connectImportButton(uploadSelector: string): void {
    // @ts-ignore
    $('#content').on('click', '.a-import', async (e: Event) => {
        e.preventDefault()
        const url = $(e.currentTarget).attr('href')
        const resp = await $.get(url)
        bootbox.dialog({
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
                    callback() {
                        // @ts-ignore
                        $(uploadSelector).uploadifive('upload')

                        return false
                    },
                },
            },
        })
    })
}

(<any>window).Autocomplete = Autocomplete;
(<any>window).AutocompleteSingle = AutocompleteSingle
