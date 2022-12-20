/**
 * Class to handle file uploads (using Uploadifive).
 */
// @ts-nocheck
import {NotifyError, NotifySuccess} from './notify'

export class FileUpload {
    static readonly TYPE_DEFAULT = '*'
    static readonly TYPE_JPG = 'image/jpeg'
    static readonly TYPE_CSV = 'text/csv'
    static readonly TYPE_XLS = 'application/vnd.ms-excel'
    static readonly TYPE_XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    static readonly TYPE_PDF = 'application/pdf'
    static readonly TYPE_DOC = 'application/msword'
    static readonly TYPE_DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'

    constructor(
        inputId: string,
        url: string,
        type?: string,
        autoUpload?: boolean,
        text?: string,
        successCallback?: () => void,
        multi = false,
    ) {
        // @ts-ignore
        $('#' + inputId).uploadifive({
            'uploadScript': url,
            'buttonText': text ? text : t.t('Wybierz pliki'),
            'fileType': type ? type : FileUpload.TYPE_DEFAULT,
            'multi': multi,
            'auto': autoUpload,
            'removeCompleted': true,
            'onUploadComplete': (file: string, data: any) => {
                if (data == 'ok') {
                    new NotifySuccess(t.t('Plik został wgrany pomyślnie.'))
                } else {
                    new NotifyError(data)
                }

                if (successCallback) {
                    successCallback()
                }
            },
            'onError': (errorType: string) => {
                new NotifyError()
            },
        })
    }
}

(<any>window).FileUpload = FileUpload