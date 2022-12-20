/**
 * Created by pawel on 09.11.2016.
 */

import * as dayjs from 'dayjs'

export class Translate {
    private cache: CacheAdapter
    private loader: LoaderAdapter

    constructor(locale: string, loader: LoaderAdapter, cache: CacheAdapter) {
        this.cache = cache
        this.loader = loader

        if (!cache.loaded() || cache.refresh()) {
            loader.load((translation: any): any => {
                cache.save(translation)
            }, cache)
        }
    }

    t(phrase: string): string {
        if (!this.cache.loaded()) {
            console.log('Cache not loaded')
            return phrase
        }
        if (!this.cache.get(phrase)) {
            console.log('Cannot find key "' + phrase + '" in local storage.')
            return phrase
        }

        // throw ReferenceError("Translations not loaded");
        return this.cache.get(phrase)
    }
}

///////// cache

/**
 * Interface for caching translation files.
 */
interface CacheAdapter {
    /**
     * Save data in cache.
     *
     * @param data
     */
    save(data: any): void;

    /**
     * Checks wheather cache is loaded.
     */
    loaded(): boolean;

    /**
     * Checks wheather cache needs refreshing.
     */
    refresh(): boolean;

    /**
     * Checks if specific key is in cache.
     *
     * @param phrase
     */
    has(phrase: string): boolean;

    /**
     * Gets specific key from cache.
     *
     * @param phrase
     */
    get(phrase: string): string;
}

class LocalStorageCache implements CacheAdapter {
    private storage: any
    private readonly ttl: number = 600

    constructor() {
        if (typeof (Storage) !== 'undefined') {
            this.storage = localStorage
        } else {
            throw ReferenceError('Local storage not supported')
        }
    }

    save(data: any): void {
        console.log('saving data to local storage...')
        this.storage.__time = dayjs().format()

        for (let t in data) {
            let tstr = t.replace(/[\r\n]/g, '')
            this.storage[tstr] = data[t]
        }
    }

    loaded(): boolean {
        return this.storage.length > 0
    }

    refresh(): boolean {
        if (this.storage.__time) {
            let timeSave = dayjs(this.storage.__time)
            let timeNow = dayjs()
            let diff = timeNow.diff(timeSave, 'minutes')

            if (diff > this.ttl) {
                console.log('refreshing cache...')
                return true
            }
        }

        return false
    }

    has(phrase: string): boolean {
        let ph = phrase.replace(/[\r\n]/g, '')
        return (this.loaded() && this.storage[ph])
    }

    get(phrase: string): string {
        let ph = phrase.replace(/[\r\n]/g, '')
        return this.has(phrase) && this.storage[ph] != 'null' ? this.storage[ph] : phrase
    }
}

class ObjectCache implements CacheAdapter {
    private storage: any

    constructor() {

    }

    save(data: any): void {
        this.storage = data
    }

    loaded(): boolean {
        return !$.isEmptyObject(this.storage)
    }

    refresh(): boolean {
        return true
    }

    has(phrase: string): boolean {
        return (this.loaded() && this.storage[phrase])
    }

    get(phrase: string): string {
        return this.has(phrase) ? this.storage[phrase] : phrase
    }
}

///////// transaltion loader

/**
 * Loads translation files.
 */
interface LoaderAdapter {
    load(callback: (resp: any) => any, cache: CacheAdapter): void;
}

class JsonAjaxLoader implements LoaderAdapter {
    private url: string = '/data/__LOCALE__.json?' + (+new Date)
    private locale: string

    constructor(locale: string) {
        this.locale = locale
    }

    load(callback: (translation: any) => any, cache: CacheAdapter): void {
        let url = this.url.replace('__LOCALE__', this.locale)

        $.get(url, callback)
    }
}

// załaduj tłumaczenie
// @ts-ignore
const loader = new JsonAjaxLoader(gLanguage)
const cache = new LocalStorageCache()
// @ts-ignore
const t = new Translate(gLanguage, loader, cache)

export default new Translate(gLanguage, loader, cache);
(<any>window).t = t
