import React from 'react'
import dayjs from 'dayjs'
import * as localizedFormat from 'dayjs/plugin/localizedFormat'
import * as relativeTime from 'dayjs/plugin/relativeTime'
import * as quarterOfYear from 'dayjs/plugin/quarterOfYear'
import * as isToday from 'dayjs/plugin/isToday'
import * as utc from 'dayjs/plugin/utc'
import 'dayjs/locale/pl'
import Providers from './providers/Providers'

dayjs.locale('pl')
// @ts-ignore
dayjs.extend(localizedFormat)
// @ts-ignore
dayjs.extend(relativeTime)
// @ts-ignore
dayjs.extend(quarterOfYear)
// @ts-ignore
dayjs.extend(utc)
// @ts-ignore
dayjs.extend(isToday)

function App() {
    return <Providers />
}

export default App
