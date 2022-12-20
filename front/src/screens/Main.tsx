import { Typography } from '@mui/material'
import { fetchAssemblyOrders } from 'api/assemblyOrders'
import AssemblyListItem from 'components/Assembly/AssemblyListItem'
import Spinner from 'components/Spinner'
import { AssemblyOrderStatus } from 'models/assemblyOrders'
import { SearchContext } from 'providers/SearchProvider'
import React, { useContext } from 'react'
import { useQuery } from 'react-query'

export const Main = () => {
    const { filters } = useContext(SearchContext)
    const { isLoading, data } = useQuery(
        ['assemblyOrders', filters.join(',')],
        () => fetchAssemblyOrders(AssemblyOrderStatus.Available, filters)
    )

    if (isLoading) return <Spinner />
    if (!data?.length) return <div>Brak zleceń</div>

    return (
        <>
            <Typography variant="h5" sx={{ marginBottom: 2 }}>
                Praca
            </Typography>

            {data!.map((o) => (
                <AssemblyListItem order={o} key={o.id} />
            ))}
        </>
    )
}

export default Main
