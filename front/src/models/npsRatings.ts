export interface NpsRating {
    id: number
    date: Date
    customerName: string
    rating: number
    comment: string
    email: string
}

export interface NpsRatingsResponse {
    data: NpsRating[]
    nextPage: number | null
}
