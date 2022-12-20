import { fetchAssemblyOrder } from 'api/assemblyOrders'
import AssemblyDetails from 'components/Assembly/AssemblyDetails'
import Spinner from 'components/Spinner'
import React from 'react'
import { useQuery } from 'react-query'
import { useParams } from 'react-router-dom'

const AssemblyOrderDetails = () => {
    const params = useParams()
    const id = +params.id!

    const { isLoading, data } = useQuery(['assemblyOrders', id], () =>
        fetchAssemblyOrder(id)
    )

    if (isLoading) return <Spinner />
    if (!data) return <div>Nie znaleziono zlecenia</div>

    return <AssemblyDetails order={data} />
}

export default AssemblyOrderDetails
