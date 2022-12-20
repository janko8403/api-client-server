/**
 * Base class to display notifications (using PNotify).
 */
import {alert} from '@pnotify/core'
import t       from './translate'

class Notify {
    static readonly TYPE_SUCCESS = 'success'
    static readonly TYPE_ERROR = 'error'

    constructor(message: string, type: 'success' | 'error' | 'notice' | 'info') {
        let title = ''

        switch (type) {
            case Notify.TYPE_SUCCESS:
                title = t.t('Sukces')
                break
            case Notify.TYPE_ERROR:
                title = t.t('Błąd')
                break
            default:
                title = t.t('Błąd')
                break
        }

        alert({
            title: title,
            text: message,
            type: type,
        })
    }
}

/**
 * Shorthand class displaying success notification.
 */
export class NotifySuccess extends Notify {
    constructor(message?: string) {
        if (!message) {
            message = t.t('Operacja przebiegła pomyślnie.')
        }

        super(message, Notify.TYPE_SUCCESS)
    }
}

/**
 * Shorthand class displaying error notification.
 */
export class NotifyError extends Notify {
    constructor(message?: string) {
        if (!message) {
            message = t.t('Wystąpił błąd. Proszę spróbować ponownie.')
        }

        super(message, Notify.TYPE_ERROR)
    }
}