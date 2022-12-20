import { fetchAssemblyOrders } from 'api/assemblyOrders'
import AssemblyListItem from 'components/Assembly/AssemblyListItem'
import Spinner from 'components/Spinner'
import { AssemblyOrderStatus } from 'models/assemblyOrders'
import React from 'react'
import { useQuery } from 'react-query'

export const MyWork = () => {
    const { isLoading, data } = useQuery('assemblyOrdersTaken', () =>
        fetchAssemblyOrders(AssemblyOrderStatus.Accepted)
    )

    if (isLoading) return <Spinner />
    if (!data?.length) return <div>Brak zleceń</div>

    return (
        <>
            {data!.map((o) => (
                <AssemblyListItem order={o} key={o.id} />
            ))}
        </>
    )
}

export default MyWork
