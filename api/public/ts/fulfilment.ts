/**
 * Created by pawel on 04.01.2017.
 */
// @ts-nocheck
import {Utils}       from './utils'
import {NotifyError} from './notify'

import * as dayjs from 'dayjs'

export class Fulfilment {
    urlLoadQuestion: string
    currentQuestionIndex: number = 0
    currentQuestionNumber: number = 1
    isLastQuestion: boolean = false
    timeOpened: any

    /**
     * Initializes UI.
     */
    initUI(): void {
        // this.disableButtons();
        this.delegateEvents()
    }

    /**
     * Loads next/previous question using AJAX request.
     *
     */

    loadQuestion(direction?: string): void {
        $.get(this.urlLoadQuestion, (resp: any) => {
            $('#question-content').html(resp.html)
            this.currentQuestionIndex = resp.currentQuestionIndex

            this.updateUI()

            if (!sessionStorage.getItem('question_open_' + this.currentQuestionIndex)) {
                sessionStorage.setItem('question_open_' + this.currentQuestionIndex, dayjs().unix())
            }
            this.timeOpened = parseInt(sessionStorage.getItem('question_open_' + this.currentQuestionIndex))

            // @ts-ignore
            Utils.initDOM(gLocale)

            $('[type=text]:not([data-provide=datepicker]), [type=number], textarea').first().focus()

        })
    }

    /**
     * Clears local storage containing question's open time.
     */
    clearLocalStorage(): void {
        sessionStorage.clear()
    }

    /**
     * Delegates UI events.
     */
    private delegateEvents(): void {
        $('#btn-next').click(() => {
            if (!this.isLastQuestion) {
                $('#question-form').trigger('submit', {
                    'action': 'next', 'success': () => {
                        this.currentQuestionNumber++
                        this.loadQuestion('next')
                    },
                })
            }
        })
        $('#btn-previous').click(() => {
            if (!this.isFirstQuestion()) {
                $('#question-form').trigger('submit', {
                    'action': 'previous', 'success': () => {
                        this.currentQuestionNumber--
                        this.loadQuestion('previous')
                    },
                })
            }
        })

        // moving to next question
        $('#question-content').on('submit', '#question-form', (e: any, p: any) => {
            let form = $(e.currentTarget)
            let action = form.attr('action')
            let params = form.serializeArray()

            params.push({'name': 'start', 'value': this.timeOpened})
            params.push({'name': 'end', 'value': dayjs().unix()})
            params.push({'name': 'action', 'value': p.action})

            $.post(action, params, (resp: any) => {
                if (resp.result) {
                    if (resp.html) {
                        // html sent to replace question content - finish fulfilment
                        $('#question-content').html(resp.html)
                        this.isLastQuestion = true
                        this.updateUI()
                    } else {
                        p.success()
                    }
                } else {
                    new NotifyError(resp.msg)
                }
            })

            return false
        })

        // show fullscreen image
        $('#question-content').on('click', '.a-show-full', (e: any) => {
            let html = '<img src=\'' + $(e.currentTarget).attr('src') + '\' class=\'img-responsive\' />'

            bootbox.alert(html)

            return false
        })

        // swipe left/right gesture
        // $("#row-elearning").swipe({
        //     swipeLeft: (event: any, direction: string, distance: any, duration: any, fingerCount: number, fingerData: any, currentDirection: any) => {
        //         $("#btn-next").click();
        //     },
        //     swipeRight: (event: any, direction: string, distance: any, duration: any, fingerCount: number, fingerData: any, currentDirection: any) => {
        //         $("#btn-previous").click();
        //     }
        // });

        $('#question-content').on('click', '#a-show-document-preview', (e: any) => {
            let href = $(e.currentTarget).attr('href')

            $.get(href, (resp: string) => {
                bootbox.dialog({
                    title: t.t('Podgląd dokumentu'),
                    message: resp,
                    size: 'large',
                    buttons: {
                        'Zamknij': () => {
                        },
                    },
                })
            })

            return false
        })

        // block send form button
        $('#question-content').on('submit', '#frm-finish', (e: any) => {
            $('#btn-finish')
                .prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i> ' + t.t('Zapisywanie, proszę czekać...'))
        })

        // mobile - hide top bar
        // @ts-ignore
        if (gIsMobile) {
            $('.navbar-static-top').hide()
            $('.site-container').css({
                'max-height': 'calc(100% - 5px)',
                'height': '100%',
            })
            $('.sl-hamburger').appendTo('body').addClass('sl-hamburger-fixed')
            $('.main-sidebar').css('height', '100%')
        }
    }

    /**
     * Updates UI.
     */
    private updateUI(): void {
        $('#question-number').text(this.currentQuestionNumber)

        if (this.isLastQuestion) {
            $('#btn-previous').prop('disabled', false).click(() => {
                this.isLastQuestion = false
                this.loadQuestion('previous')
            })
        } else {
            $('#btn-previous').prop('disabled', this.isFirstQuestion())
        }
        $('#btn-next').prop('disabled', this.isLastQuestion)
    }

    /**
     * Disables next/previous buttons.
     */
    private disableButtons(): void {
        $('#btn-next').prop('disabled', true)
        $('#btn-previous').prop('disabled', true)
    }

    /**
     * Checks wheather current question is the first one.
     *
     * @returns {boolean}
     */
    private isFirstQuestion(): boolean {
        return this.currentQuestionNumber <= 1
    }
}