import { NpsRating, NpsRatingsResponse } from 'models/npsRatings'
import api from './api'

export const fetchNpsRatings = async (page: number) => {
    const resp = await api.get<NpsRatingsResponse>(`/api/nps?page=${page}`)

    return resp.data
}

export const fetchNpsRating = async (id: number) => {
    const resp = await api.get<NpsRating>(`/api/nps/${id}`)

    return resp.data
}
