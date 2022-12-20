import { Button } from '@mui/material'
import { fetchNpsRating } from 'api/npsRatings'
import Spinner from 'components/Spinner'
import dayjs from 'dayjs'
import React from 'react'
import { useQuery } from 'react-query'
import { useNavigate, useParams } from 'react-router-dom'

const NpsRatingDetails = () => {
    const navigate = useNavigate()
    const params = useParams()
    const id = +params.id!

    const { isLoading, data } = useQuery(['npsRatings', id], () =>
        fetchNpsRating(id)
    )

    if (isLoading) return <Spinner />
    if (!data) return <div>Nie znaleziono ankiety</div>

    return (
        <>
            <h2>{dayjs(data.date).format('LLL')}</h2>
            <h3>{data.customerName}</h3>
            <h4>
                Ocena: <strong>{data.rating}</strong>
            </h4>
            <p>{data.comment}</p>

            <Button onClick={() => navigate(-1)}>Wstecz</Button>
        </>
    )
}

export default NpsRatingDetails
