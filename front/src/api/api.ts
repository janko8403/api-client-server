import axios from 'axios'
import dayjs from 'dayjs'

const instance = axios.create({
    baseURL: process.env.REACT_APP_API_URL,
})

instance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('access_token') || ''
        if (token && config.headers) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (err) => {
        return Promise.reject(err)
    }
)

const isoDateFormat =
    /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d*)?(?:[-+]\d{2}:?\d{2}|Z)?$/

function isIsoDateString(value: any): boolean {
    return value && typeof value === 'string' && isoDateFormat.test(value)
}

function handleDates(body: any) {
    if (body === null || body === undefined || typeof body !== 'object')
        return body

    for (const key of Object.keys(body)) {
        const value = body[key]
        if (isIsoDateString(value)) body[key] = dayjs(value)
        else if (typeof value === 'object') handleDates(value)
    }
}

instance.interceptors.response.use((originalResponse) => {
    handleDates(originalResponse.data)
    return originalResponse
})

// instance.interceptors.response.use(
//     (value) => value,
//     (err) => {
//         if (
//             err.response?.status === 401 &&
//             !err.request.responseURL.includes('/profile')
//         ) {
//             // unauthorized
//             localStorage.removeItem('accessToken')
//             window.location.href = '/'
//         }

//         throw err
//     }
// )

export default instance
